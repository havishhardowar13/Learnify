<?php
// logout.php - Enhanced user logout page
session_start();
require_once 'includes/config.php';

$page_title = "Logout - Learnify";

// Check if this is a confirmed logout request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_logout'])) {
    performLogout();
    exit();
}

// If not confirmed, show logout confirmation page
require_once 'includes/header.php';

$user_name = $_SESSION['first_name'] ?? 'User';

// If user is not logged in but somehow reached this page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<section class="section" style="min-height: 70vh;">
    <div class="section-header">
        <h2>Logout Confirmation</h2>
        <hr class="divider">
    </div>
    
    <div style="max-width: 500px; margin: 0 auto; background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow); text-align: center;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 2rem;">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        
        <h3 style="color: var(--text-dark); margin-bottom: 1rem;">Are you sure you want to logout?</h3>
        
        <p style="color: var(--text-light); margin-bottom: 2rem; line-height: 1.6;">
            Hi <?php echo htmlspecialchars($user_name); ?>, you're about to logout from your account. 
            You'll need to login again to access your courses and dashboard.
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="display: inline;">
                <input type="hidden" name="confirm_logout" value="1">
                <button type="submit" class="btn" style="background: #dc3545; color: white; border: none; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-sign-out-alt"></i> Yes, Logout
                </button>
            </form>
            
            <a href="dashboard.php" class="btn" style="background: var(--background); color: var(--text-dark); text-decoration: none; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; border: 2px solid #e2e8f0; transition: all 0.3s ease;">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
        
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
            <p style="color: var(--text-light); font-size: 0.9rem;">
                <i class="fas fa-info-circle"></i> You'll be redirected to the login page after logout.
            </p>
        </div>
    </div>
</section>

<script>
// Auto-redirect after 30 seconds if user doesn't act
setTimeout(function() {
    window.location.href = 'dashboard.php';
}, 30000);
</script>

<?php 
require_once 'includes/footer.php';

// Function to perform the actual logout
function performLogout() {
    global $db;
    
    $user_id = $_SESSION['user_id'] ?? null;
    
    if ($user_id) {
        // Clear remember me token if exists
        if (isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            $hashed_token = hash('sha256', $token);
            
            try {
                $stmt = $db->prepare("DELETE FROM user_tokens WHERE token = ?");
                $stmt->execute([$hashed_token]);
            } catch (PDOException $e) {
                error_log("Error removing remember token: " . $e->getMessage());
            }
            
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        // Log logout activity
        try {
            $activity_stmt = $db->prepare("INSERT INTO user_activities (user_id, action) VALUES (?, ?)");
            $activity_stmt->execute([$user_id, 'User logged out']);
        } catch (PDOException $e) {
            error_log("Error logging logout activity: " . $e->getMessage());
        }
    }
    
    // Clear session
    $_SESSION = [];
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
    
    // Set success message in session for next page
    session_start();
    $_SESSION['success'] = 'You have been successfully logged out.';
    
    // Redirect to login page
    header('Location: login.php');
    exit();
}
?>
