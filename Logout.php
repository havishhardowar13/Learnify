<?php

session_start();

$_SESSION = [];

session_destroy();

header("Location: Login.php?message=You have been logged out successfully");
exit();
?>


