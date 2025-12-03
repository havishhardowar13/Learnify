<?php
session_start();
require 'includes/config.php';


$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');



if (empty($email) || empty($password)) {
    die(json_encode(["status" => "error", "message" => "Email and password are required"]));
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die(json_encode(["status" => "error", "message" => "Invalid email format"]));
}


$stmt = $conn->prepare("SELECT user_id, email, password_hash, role, is_active FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if (!$user) {
    die(json_encode(["status" => "error", "message" => "User not found"]));
}


if ($user['is_active'] != 1) {
    die(json_encode(["status" => "error", "message" => "Account is inactive"]));
}


if (!password_verify($password, $user['password_hash'])) {
    die(json_encode(["status" => "error", "message" => "Invalid password"]));
}


$_SESSION['user_id'] = $user['user_id'];
$_SESSION['email'] = $user['email'];
$_SESSION['role'] = $user['role'];

echo json_encode(["status" => "success", "message" => "Login successful"]);
?>






