<?php
// instructor/dashboard.php
require_once '../includes/config.php';
checkAuth('instructor');

$page_title = "Instructor Dashboard - Learnify";
require_once '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Get instructor statistics
$stats = [
    'total_courses' => $db->query("SELECT COUNT(*) FROM courses WHERE instructor_id = $user_id")->fetchColumn(),
    'total_students' => $db->query("SELECT COUNT(DISTINCT e.student_id) FROM enrollments e JOIN courses c ON e.course_id = c.course_id WHERE c.instructor_id = $user_id")->fetchColumn(),
    'total_revenue' => $db->query("SELECT SUM(p.amount) FROM payments p JOIN courses c ON p.course_id = c.course_id WHERE c.instructor_id = $user_id AND p.payment_status = 'completed'")->fetchColumn() ?? 0,
];
?>

<div class="section">
    <div class="section-header" style="text-align: left;">
        <h2>Instructor Dashboard</h2>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</p>
    </div>
    
    <!-- Instructor Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid var(--primary);">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">My Courses</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 2rem; font-weight: 700; color: var(--text-dark);"><?php echo $stats['total_courses']; ?></span>
                <i class="fas fa-chalkboard-teacher" style="color: var(--primary); font-size: 1.5rem;"></i>
            </div>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid #28a745;">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Students</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 2rem; font-weight: 700; color: var(--text-dark);"><?php echo $stats['total_students']; ?></span>
                <i class="fas fa-user-graduate" style="color: #28a745; font-size: 1.5rem;"></i>
            </div>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid #ffc107;">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Earnings</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 2rem; font-weight: 700; color: var(--text-dark);">$<?php echo number_format($stats['total_revenue'], 2); ?></span>
                <i class="fas fa-dollar-sign" style="color: #ffc107; font-size: 1.5rem;"></i>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow); margin-bottom: 2rem;">
        <h3 style="color: var(--text-dark); margin-bottom: 1.5rem;">Quick Actions</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <a href="create-course.php" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--background); border-radius: 8px; text-decoration: none; color: var(--text-dark); transition: all 0.3s ease;">
                <i class="fas fa-plus-circle" style="color: var(--primary); font-size: 1.2rem;"></i>
                <span>Create New Course</span>
            </a>
            <a href="my-courses.php" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--background); border-radius: 8px; text-decoration: none; color: var(--text-dark); transition: all 0.3s ease;">
                <i class="fas fa-book" style="color: #28a745; font-size: 1.2rem;"></i>
                <span>My Courses</span>
            </a>
            <a href="manage-students.php" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--background); border-radius: 8px; text-decoration: none; color: var(--text-dark); transition: all 0.3s ease;">
                <i class="fas fa-users" style="color: #17a2b8; font-size: 1.2rem;"></i>
                <span>Manage Students</span>
            </a>
            <a href="earnings.php" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--background); border-radius: 8px; text-decoration: none; color: var(--text-dark); transition: all 0.3s ease;">
                <i class="fas fa-chart-line" style="color: #ffc107; font-size: 1.2rem;"></i>
                <span>View Earnings</span>
            </a>
        </div>
    </div>
    
    <!-- My Courses -->
    <div style="margin-bottom: 3rem;">
        <h3 style="color: var(--text-dark); margin-bottom: 1.5rem;">My Recent Courses</h3>
        <div class="courses-grid">
            <?php
            $courses = $db->query("
                SELECT c.*, 
                       (SELECT COUNT(*) FROM enrollments WHERE course_id = c.course_id) as student_count,
                       (SELECT SUM(amount) FROM payments p WHERE p.course_id = c.course_id AND p.payment_status = 'completed') as revenue
                FROM courses c 
                WHERE c.instructor_id = $user_id 
                ORDER BY c.created_at DESC 
                LIMIT 4
            ")->fetchAll();
            
            foreach ($courses as $course): ?>
                <div class="course-card fade-in-up">
                    <div class="course-badge"><?php echo $course['is_published'] ? 'Published' : 'Draft'; ?></div>
                    <div class="course-image">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="course-content">
                        <span class="course-category">Instructor</span>
                        <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <div class="course-stats">
                            <span><i class="fas fa-user-graduate"></i> <?php echo $course['student_count']; ?> students</span>
                            <span><i class="fas fa-dollar-sign"></i> $<?php echo number_format($course['revenue'] ?? 0, 2); ?></span>
                        </div>
                        <p class="course-description"><?php echo htmlspecialchars(substr($course['description'] ?? 'No description', 0, 100)); ?>...</p>
                        <div class="course-price">
                            <span class="price">$<?php echo number_format($course['price'], 2); ?></span>
                            <a href="course.php?id=<?php echo $course['course_id']; ?>" class="enroll-btn">Manage</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (count($courses) == 0): ?>
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 15px; box-shadow: var(--card-shadow);">
                <i class="fas fa-chalkboard-teacher" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-dark); margin-bottom: 1rem;">No Courses Created</h3>
                <p style="color: var(--text-light); margin-bottom: 1.5rem;">Start creating your first course to share your knowledge</p>
                <a href="create-course.php" class="enroll-btn">Create Course</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
