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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <a href="index.php" class="logo">Learnify</a>
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="courses.php" class="<?php echo $current_page == 'courses.php' ? 'active' : ''; ?>">Courses</a></li>
                <li><a href="about.php" class="<?php echo $current_page == 'about.php' ? 'active' : ''; ?>">About</a></li>
                <li><a href="contact.php" class="<?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li><a href="admin/dashboard.php">Admin</a></li>
                    <?php elseif (isInstructor()): ?>
                        <li><a href="instructor/dashboard.php">Instructor</a></li>
                    <?php else: ?>
                        <li><a href="student/dashboard.php">My Courses</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <!-- In the auth-buttons section of header.php -->
            <div class="auth-buttons">
                <?php if (isLoggedIn()): ?>
                    <div class="user-menu">
                        <span style="color: white; margin-right: 1rem;">
                            <i class="fas fa-user"></i> 
                            <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'User'); ?>
                        </span>
                        <a href="dashboard.php" class="btn btn-outline">Dashboard</a>
                        <a href="logout.php" class="btn btn-primary">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline">Login</a>
                    <a href="register.php" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Display flash messages -->
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
