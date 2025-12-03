<?php
// dashboard.php - Place in root directory
require_once 'includes/config.php';

if (!isLoggedIn()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect('login.php');
}

// Redirect based on user role
switch ($_SESSION['user_role']) {
    case 'admin':
        redirect('admin/dashboard.php');
        break;
    case 'instructor':
        redirect('instructor/dashboard.php');
        break;
    case 'student':
        redirect('student/dashboard.php');
        break;
    default:
        // Logout if role is invalid
        session_destroy();
        redirect('login.php');
}
?>
