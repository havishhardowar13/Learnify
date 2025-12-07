<?php
// login.php
require 'includes/config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors['general'] = 'Email and password are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['general'] = 'Invalid email format';
    } else {
        try {
            // FIXED: Added first_name and last_name to the SELECT statement
            $sql  = "SELECT user_id, email, password_hash, role, is_active, first_name, last_name FROM users WHERE email = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Login query error: ' . $e->getMessage());
            $errors['general'] = 'System error. Please try again later.';
            $user = false;
        }

        if ($user) {
            if ((int)$user['is_active'] !== 1) {
                $errors['general'] = 'Account is inactive';
            } elseif (!password_verify($password, $user['password_hash'])) {
                $errors['general'] = 'Invalid email or password';
            } else {
                // Now these will exist because we selected them
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                $success = true;
                header('Location: dashboard.php');
                exit;
            }
        } else {
            $errors['general'] = 'Invalid email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Learnify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header class="header">
    <nav class="navbar">
        <a href="index.php" class="logo">Learnify</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="login.php" class="btn btn-outline">Login</a>
            <a href="register.php" class="btn btn-primary">Register</a>
        </div>
    </nav>
</header>

<section class="section">
    <div class="section-header">
        <h2>Login to your account</h2>
        <hr class="divider">
        <p>Enter your email and password to continue.</p>
    </div>

    <div style="max-width: 400px; margin: 0 auto; background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow);">
        <?php if (!empty($errors['general'])): ?>
            <div style="background:#f8d7da;color:#721c24;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;">
                <?php echo htmlspecialchars($errors['general']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       required>
            </div>

            <button type="submit" class="btn enroll-btn" style="width:100%;">Login</button>
        </form>

        <p style="margin-top:1rem; text-align:center; color:var(--text-light);">
            Don't have an account? <a href="register.php">Register here</a>.
        </p>
    </div>
</section>

<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Learnify. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
