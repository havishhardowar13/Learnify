<?php
// header.php - Common header with authentication
require_once 'config.php';

$current_page = basename($_SERVER['PHP_SELF']);
$page_title = $page_title ?? 'Learnify - Learn Skills for Your Future';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/dashboard.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
<header class="header">
    <nav class="navbar">

        <a href="<?php echo BASE_URL; ?>index.php" class="logo">Learnify</a>

        <ul class="nav-links">
            <li>
                <a href="<?php echo BASE_URL; ?>index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                    Home
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL; ?>courses.php" class="<?php echo $current_page == 'courses.php' ? 'active' : ''; ?>">
                    Courses
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL; ?>about.php" class="<?php echo $current_page == 'about.php' ? 'active' : ''; ?>">
                    About
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL; ?>contact.php" class="<?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">
                    Contact
                </a>
            </li>

            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <li><a href="<?php echo BASE_URL; ?>admin/dashboard.php">Dashboard</a></li>
                <?php elseif (isInstructor()): ?>
                    <li><a href="<?php echo BASE_URL; ?>instructor/dashboard.php">Dashboard</a></li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>student/dashboard.php">Dashboard</a></li>
                <?php endif; ?>

                <li><a href="<?php echo BASE_URL; ?>logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>

        <div class="auth-buttons">
            <?php if (isLoggedIn()): ?>
                <span style="color: white; margin-right: 1rem;">
                    <i class="fas fa-user"></i>
                    <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'User'); ?>
                </span>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-outline">Login</a>
                <a href="<?php echo BASE_URL; ?>register.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>

    </nav>
</header>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" style="margin: 0; border-radius: 0; text-align: center;">
        <?php echo htmlspecialchars($_SESSION['success']); ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error" style="margin: 0; border-radius: 0; text-align: center;">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
