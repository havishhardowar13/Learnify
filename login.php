<?php
require 'includes/config.php';
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
        <div id="loginMessage"
             style="display:none; margin-bottom:1rem; padding:0.75rem 1rem; border-radius:8px; font-size:0.9rem;"></div>

        <form id="loginForm" method="POST" action="login_process.php" novalidate>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       required>
                <div class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       required>
                <div class="error-message"></div>
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

<script>
// Submit login form via fetch to get JSON response and show message
document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const msgBox = document.getElementById('loginMessage');

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
        .then(r => r.json())
        .then(data => {
            msgBox.style.display = 'block';
            if (data.status === 'success') {
                msgBox.style.background = '#d4edda';
                msgBox.style.color = '#155724';
                msgBox.textContent = data.message || 'Login successful';

                // Redirect to dashboard after short delay
                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 800);
            } else {
                msgBox.style.background = '#f8d7da';
                msgBox.style.color = '#721c24';
                msgBox.textContent = data.message || 'Login failed';
            }
        })
        .catch(() => {
            msgBox.style.display = 'block';
            msgBox.style.background = '#f8d7da';
            msgBox.style.color = '#721c24';
            msgBox.textContent = 'System error. Please try again.';
        });
});
</script>
</body>
</html>
