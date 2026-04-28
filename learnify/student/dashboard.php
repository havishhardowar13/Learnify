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
    <div class="section-header section-header-left">
        <h2>Welcome Back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h2>
        <p>Continue your learning journey</p>
    </div>
    
    <!-- Student Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-card-primary">
            <h3 class="stat-header">Enrolled Courses</h3>
            <div class="stat-content">
                <span class="stat-number"><?php echo $stats['enrolled_courses']; ?></span>
                <i class="fas fa-book stat-icon" style="color: var(--primary);"></i>
            </div>
        </div>
        
        <div class="stat-card stat-card-success">
            <h3 class="stat-header">Completed</h3>
            <div class="stat-content">
                <span class="stat-number"><?php echo $stats['completed_courses']; ?></span>
                <i class="fas fa-check-circle stat-icon" style="color: #28a745;"></i>
            </div>
        </div>
        
        <div class="stat-card stat-card-warning">
            <h3 class="stat-header">In Progress</h3>
            <div class="stat-content">
                <span class="stat-number"><?php echo $stats['in_progress']; ?></span>
                <i class="fas fa-spinner stat-icon" style="color: #ffc107;"></i>
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
            <div class="empty-state">
                <i class="fas fa-book empty-state-icon"></i>
                <h3 class="empty-state-title">No Courses Yet</h3>
                <p class="empty-state-text">Start your learning journey by enrolling in a course</p>
                <a href="../courses.php" class="enroll-btn">Browse Courses</a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Recent Activity -->
    <div class="recent-activity">
        <h3 class="recent-activity-header">Recent Activity</h3>
        <?php
        $activities = $db->query("
            SELECT * FROM student_progress 
            WHERE student_id = $user_id 
            ORDER BY last_accessed DESC 
            LIMIT 5
        ")->fetchAll();
        
        if (count($activities) > 0): ?>
            <div class="activity-list">
                <?php foreach ($activities as $activity): ?>
                    <div class="activity-item">
                        <i class="fas fa-play-circle activity-icon"></i>
                        <div style="flex: 1;">
                            <div class="activity-title">Accessed Lesson</div>
                            <small class="activity-time">
                                Last accessed: <?php echo date('M d, Y H:i', strtotime($activity['last_accessed'])); ?>
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state-small">
                <i class="fas fa-history empty-state-icon" style="font-size: 2rem;"></i>
                <p style="color: var(--text-light);">No recent activity</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
