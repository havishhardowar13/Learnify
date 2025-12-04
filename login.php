<?php
// login_process.php
require 'includes/config.php'; // starts session, sets $db, helpers, etc.

header('Content-Type: application/json');

// Helper to send JSON responses
function respond($status, $message, $extra = []) {
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
    exit;
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond('error', 'Invalid request method');
}

// Read and sanitize input
$email    = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Basic validation
if ($email === '' || $password === '') {
    respond('error', 'Email and password are required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond('error', 'Invalid email format');
}

// Look up user
try {
    $sql  = "SELECT user_id, email, password_hash, role, is_active FROM users WHERE email = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    error_log('Login query error: ' . $e->getMessage());
    respond('error', 'System error. Please try again later.');
}

if (!$user) {
    // For security you could say "Invalid email or password" instead
    respond('error', 'User not found');
}

if ((int)$user['is_active'] !== 1) {
    respond('error', 'Account is inactive');
}

if (!password_verify($password, $user['password_hash'])) {
    respond('error', 'Invalid password');
}

// Set session
$_SESSION['user_id']   = $user['user_id'];
$_SESSION['email']     = $user['email'];
$_SESSION['user_role'] = $user['role']; // matches config.php helpers

respond('success', 'Login successful');
