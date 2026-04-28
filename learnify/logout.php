<?php
// logout.php - Simpler version
require_once 'includes/config.php';

// Immediately destroy the session
session_destroy();

// Clear any existing session cookies
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to login
header("Location: login.php");
exit();
?>
