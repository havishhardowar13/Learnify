<?php
// login.php - User login page
$page_title = "Login - Learnify";
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Validation
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } else {
        // Check user in database
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            
            // Update last login
            $updateStmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
            $updateStmt->execute([$user['user_id']]);
            
            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expiry = time() + (30 * 24 * 60 * 60); // 30 days
                setcookie('remember_token', $token, $expiry, '/');
                
                // Store token in database
                $tokenStmt = $db->prepare("INSERT INTO user_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
                $tokenStmt->execute([$user['user_id'], hash('sha256', $token), date('Y-m-d H:i:s', $expiry)]);
            }
            
            // Redirect based on role
            $_SESSION['success'] = "Welcome back, " . $user['first_name'] . "!";
            redirect('dashboard.php');
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>

<section class="section" style="min-height: 70vh;">
    <div class="section-header">
        <h2>Login to Your Account</h2>
        <hr class="divider">
        <p>Access your courses and learning dashboard</p>
    </div>
    
    <div style="max-width: 400px; margin: 0 auto; background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow);">
        <?php if ($error): ?>
            <div style="background: #fee; color: #c33; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #fcc;">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control" 
                       value="<?php echo htmlspecialchars($email); ?>"
                       required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;">
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control" 
                       required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="remember" id="remember">
                    <span style="color: var(--text-light);">Remember me</span>
                </label>
                <a href="forgot-password.php" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn enroll-btn" style="width: 100%; padding: 0.75rem; font-size: 1rem;">Login</button>
        </form>
        
        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
            <p style="color: var(--text-light);">Don't have an account? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign up here</a></p>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>