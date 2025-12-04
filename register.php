<?php
// register.php
require 'includes/config.php'; // starts session, sets $db, helpers, CSRF, etc.

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($token)) {
        $errors['general'] = 'Invalid form token, please refresh the page and try again.';
    } else {
        // Sanitize inputs
        $first_name = sanitizeInput($_POST['first_name'] ?? '');
        $last_name  = sanitizeInput($_POST['last_name'] ?? '');
        $email      = sanitizeInput($_POST['email'] ?? '');
        $password   = $_POST['password'] ?? '';
        $confirm    = $_POST['confirm_password'] ?? '';

        // Default role for signup
        $role = 'student';

        // Validation
        if ($first_name === '') {
            $errors['first_name'] = 'First name is required';
        }

        if ($last_name === '') {
            $errors['last_name'] = 'Last name is required';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long';
        }

        if ($confirm === '') {
            $errors['confirm_password'] = 'Please confirm your password';
        } elseif ($confirm !== $password) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        // If no validation errors yet, check email uniqueness and insert
        if (empty($errors)) {
            try {
                // Check if email already exists
                $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $existing = $stmt->fetch();

                if ($existing) {
                    $errors['email'] = 'This email is already registered';
                } else {
                    // Create user
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $db->prepare("
                        INSERT INTO users (email, password_hash, first_name, last_name, role, is_active)
                        VALUES (?, ?, ?, ?, ?, 1)
                    ");
                    $stmt->execute([$email, $password_hash, $first_name, $last_name, $role]);

                    $user_id = $db->lastInsertId();

                    // Auto-login after registration
                    $_SESSION['user_id']   = $user_id;
                    $_SESSION['email']     = $email;
                    $_SESSION['user_role'] = $role;

                    $success = true;
                }
            } catch (PDOException $e) {
                error_log('Signup error: ' . $e->getMessage());
                $errors['general'] = 'System error. Please try again later.';
            }
        }
    }
}

// Generate CSRF token for the form
$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Learnify</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- Header (simple version; adapt to your header include if you want) -->
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
            <h2>Create your account</h2>
            <hr class="divider">
            <p>Join Learnify and start learning new skills today.</p>
        </div>

        <div style="max-width: 500px; margin: 0 auto; background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow);">
            <?php if (!empty($errors['general'])): ?>
                <div class="alert-success" style="background:#f8d7da;color:#721c24;border-color:#f5c6cb;">
                    <?php echo htmlspecialchars($errors['general']); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    Registration successful! You are now logged in.
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text"
                           id="first_name"
                           name="first_name"
                           class="form-control <?php echo isset($errors['first_name']) ? 'error' : ''; ?>"
                           value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                           required>
                    <?php if (isset($errors['first_name'])): ?>
                        <div class="error-message show"><?php echo htmlspecialchars($errors['first_name']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text"
                           id="last_name"
                           name="last_name"
                           class="form-control <?php echo isset($errors['last_name']) ? 'error' : ''; ?>"
                           value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>"
                           required>
                    <?php if (isset($errors['last_name'])): ?>
                        <div class="error-message show"><?php echo htmlspecialchars($errors['last_name']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control <?php echo isset($errors['email']) ? 'error' : ''; ?>"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-message show"><?php echo htmlspecialchars($errors['email']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control <?php echo isset($errors['password']) ? 'error' : ''; ?>"
                           required>
                    <?php if (isset($errors['password'])): ?>
                        <div class="error-message show"><?php echo htmlspecialchars($errors['password']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password"
                           id="confirm_password"
                           name="confirm_password"
                           class="form-control <?php echo isset($errors['confirm_password']) ? 'error' : ''; ?>"
                           required>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <div class="error-message show"><?php echo htmlspecialchars($errors['confirm_password']); ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn enroll-btn" style="width:100%;">Sign Up</button>
            </form>

            <p style="margin-top:1rem; text-align:center; color:var(--text-light);">
                Already have an account? <a href="login.php">Login here</a>.
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
