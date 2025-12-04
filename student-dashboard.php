<?php
// student/dashboard.php
require_once '../includes/config.php';
checkAuth('student');

$page_title = "Student Dashboard - Learnify";
require_once '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Get student statistics
$stats = [
    'enrolled_courses' => $db->query("SELECT COUNT(*) FROM enrollments WHERE student_id = $user_id")->fetchColumn(),
    'completed_courses' => $db->query("SELECT COUNT(*) FROM enrollments WHERE student_id = $user_id AND completion_status = 'completed'")->fetchColumn(),
    'in_progress' => $db->query("SELECT COUNT(*) FROM enrollments WHERE student_id = $user_id AND completion_status = 'in_progress'")->fetchColumn(),
];
?>

<div class="section">
    <div class="section-header" style="text-align: left;">
        <h2>Welcome Back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h2>
        <p>Continue your learning journey</p>
    </div>
    
    <!-- Student Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid var(--primary);">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">Enrolled Courses</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 2rem; font-weight: 700; color: var(--text-dark);"><?php echo $stats['enrolled_courses']; ?></span>
                <i class="fas fa-book" style="color: var(--primary); font-size: 1.5rem;"></i>
            </div>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid #28a745;">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">Completed</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 2rem; font-weight: 700; color: var(--text-dark);"><?php echo $stats['completed_courses']; ?></span>
                <i class="fas fa-check-circle" style="color: #28a745; font-size: 1.5rem;"></i>
            </div>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid #ffc107;">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">In Progress</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 2rem; font-weight: 700; color: var(--text-dark);"><?php echo $stats['in_progress']; ?></span>
                <i class="fas fa-spinner" style="color: #ffc107; font-size: 1.5rem;"></i>
            </div>
        </div>
    </div>
    
    <!-- Enrolled Courses -->
    <div style="margin-bottom: 3rem;">
        <h3 style="color: var(--text-dark); margin-bottom: 1.5rem;">Your Courses</h3>
        <div class="courses-grid">
            <?php
            $courses = $db->query("
                SELECT c.*, e.completion_status, e.enrolled_at 
                FROM courses c 
                JOIN enrollments e ON c.course_id = e.course_id 
                WHERE e.student_id = $user_id 
                ORDER BY e.enrolled_at DESC 
                LIMIT 4
            ")->fetchAll();
            
            foreach ($courses as $course): ?>
                <div class="course-card fade-in-up">
                    <div class="course-badge"><?php echo ucfirst($course['completion_status']); ?></div>
                    <div class="course-image">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="course-content">
                        <span class="course-category">Course</span>
                        <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p class="course-description"><?php echo htmlspecialchars(substr($course['description'] ?? 'No description', 0, 100)); ?>...</p>
                        <div class="course-price">
                            <span style="color: var(--text-light); font-size: 0.9rem;">
                                Enrolled: <?php echo date('M d, Y', strtotime($course['enrolled_at'])); ?>
                            </span>
                            <a href="course.php?id=<?php echo $course['course_id']; ?>" class="enroll-btn">Continue</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (count($courses) == 0): ?>
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 15px; box-shadow: var(--card-shadow);">
                <i class="fas fa-book" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-dark); margin-bottom: 1rem;">No Courses Yet</h3>
                <p style="color: var(--text-light); margin-bottom: 1.5rem;">Start your learning journey by enrolling in a course</p>
                <a href="../courses.php" class="enroll-btn">Browse Courses</a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Recent Activity -->
    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow);">
        <h3 style="color: var(--text-dark); margin-bottom: 1.5rem;">Recent Activity</h3>
        <?php
        $activities = $db->query("
            SELECT * FROM student_progress 
            WHERE student_id = $user_id 
            ORDER BY last_accessed DESC 
            LIMIT 5
        ")->fetchAll();
        
        if (count($activities) > 0): ?>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($activities as $activity): ?>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--background); border-radius: 8px;">
                        <i class="fas fa-play-circle" style="color: var(--primary); font-size: 1.2rem;"></i>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: var(--text-dark);">Accessed Lesson</div>
                            <small style="color: var(--text-light);">
                                Last accessed: <?php echo date('M d, Y H:i', strtotime($activity['last_accessed'])); ?>
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-history" style="font-size: 2rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                <p style="color: var(--text-light);">No recent activity</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
