<?php
// admin/dashboard.php
require_once '../includes/config.php';
checkAuth('admin');

$page_title = "Admin Dashboard - Learnify";
require_once '../includes/header.php';

// Get statistics
$stats = [
    'total_users' => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'total_courses' => $db->query("SELECT COUNT(*) FROM courses")->fetchColumn(),
    'total_enrollments' => $db->query("SELECT COUNT(*) FROM enrollments")->fetchColumn(),
    'total_revenue' => $db->query("SELECT SUM(amount) FROM payments WHERE payment_status = 'completed'")->fetchColumn() ?? 0,
];
?>

<div class="section">
    <div class="section-header" style="text-align: left;">
        <h2>Admin Dashboard</h2>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</p>
    </div>
    
    <!-- Stats Overview -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid var(--primary);">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Users</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 2rem; font-weight: 700; color: var(--text-dark);"><?php echo $stats['total_users']; ?></span>
                <i class="fas fa-users" style="color: var(--primary); font-size: 1.5rem;"></i>
            </div>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid #28a745;">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Courses</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 2rem; font-weight: 700; color: var(--text-dark);"><?php echo $stats['total_courses']; ?></span>
                <i class="fas fa-book" style="color: #28a745; font-size: 1.5rem;"></i>
            </div>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid #17a2b8;">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Enrollments</h3>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 2rem; font-weight: 700; color: var(--text-dark);"><?php echo $stats['total_enrollments']; ?></span>
                <i class="fas fa-user-graduate" style="color: #17a2b8; font-size: 1.5rem;"></i>
            </div>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: var(--card-shadow); border-left: 4px solid #ffc107;">
            <h3 style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Revenue</h3>
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
            <a href="manage-users.php" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--background); border-radius: 8px; text-decoration: none; color: var(--text-dark); transition: all 0.3s ease;">
                <i class="fas fa-users-cog" style="color: var(--primary); font-size: 1.2rem;"></i>
                <span>Manage Users</span>
            </a>
            <a href="manage-courses.php" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--background); border-radius: 8px; text-decoration: none; color: var(--text-dark); transition: all 0.3s ease;">
                <i class="fas fa-book" style="color: #28a745; font-size: 1.2rem;"></i>
                <span>Manage Courses</span>
            </a>
            <a href="view-reports.php" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--background); border-radius: 8px; text-decoration: none; color: var(--text-dark); transition: all 0.3s ease;">
                <i class="fas fa-chart-bar" style="color: #17a2b8; font-size: 1.2rem;"></i>
                <span>View Reports</span>
            </a>
            <a href="system-settings.php" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--background); border-radius: 8px; text-decoration: none; color: var(--text-dark); transition: all 0.3s ease;">
                <i class="fas fa-cog" style="color: #6c757d; font-size: 1.2rem;"></i>
                <span>System Settings</span>
            </a>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow);">
        <h3 style="color: var(--text-dark); margin-bottom: 1.5rem;">Recent Activity</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--background);">
                        <th style="padding: 1rem; text-align: left; color: var(--text-dark); border-bottom: 2px solid #e2e8f0;">User</th>
                        <th style="padding: 1rem; text-align: left; color: var(--text-dark); border-bottom: 2px solid #e2e8f0;">Action</th>
                        <th style="padding: 1rem; text-align: left; color: var(--text-dark); border-bottom: 2px solid #e2e8f0;">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $activities = $db->query("
                        SELECT u.first_name, u.last_name, u.email, a.action, a.created_at 
                        FROM user_activities a 
                        JOIN users u ON a.user_id = u.user_id 
                        ORDER BY a.created_at DESC 
                        LIMIT 10
                    ")->fetchAll();
                    
                    foreach ($activities as $activity): ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 1rem;">
                                <div><?php echo htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']); ?></div>
                                <small style="color: var(--text-light);"><?php echo htmlspecialchars($activity['email']); ?></small>
                            </td>
                            <td style="padding: 1rem; color: var(--text-dark);"><?php echo htmlspecialchars($activity['action']); ?></td>
                            <td style="padding: 1rem; color: var(--text-light);"><?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
