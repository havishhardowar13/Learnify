<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Learnify'); ?> – Learnify</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<header class="header">
    <nav class="navbar">
        <a href="<?php echo e(route('home')); ?>" class="logo">Learnify</a>

        <ul class="nav-links">
            <li><a href="<?php echo e(route('home')); ?>"         class="<?php echo e(request()->routeIs('home')    ? 'active' : ''); ?>">Home</a></li>
            <li><a href="<?php echo e(route('courses.index')); ?>" class="<?php echo e(request()->routeIs('courses*') ? 'active' : ''); ?>">Courses</a></li>
            <li><a href="<?php echo e(route('about')); ?>"         class="<?php echo e(request()->routeIs('about')   ? 'active' : ''); ?>">About</a></li>
            <li><a href="<?php echo e(route('contact')); ?>"       class="<?php echo e(request()->routeIs('contact') ? 'active' : ''); ?>">Contact</a></li>

            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isAdmin()): ?>
                    <li><a href="<?php echo e(route('dashboard.admin')); ?>">Admin Dashboard</a></li>
                <?php elseif(auth()->user()->isInstructor()): ?>
                    <li><a href="<?php echo e(route('dashboard.instructor')); ?>">My Dashboard</a></li>
                <?php else: ?>
                    <li><a href="<?php echo e(route('dashboard.student')); ?>">My Dashboard</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>

        <div class="auth-buttons">
            <?php if(auth()->guard()->check()): ?>
                <span class="user-greeting">
                    <i class="fas fa-user"></i>
                    <?php echo e(auth()->user()->first_name); ?>

                    <span class="role-badge role-<?php echo e(auth()->user()->role); ?>"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                </span>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>"    class="btn btn-outline">Login</a>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>
</header>


<?php if(session('success')): ?>
    <div class="flash flash-success">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="flash flash-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

        <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
<?php endif; ?>
<?php if(session('info')): ?>
    <div class="flash flash-info">
        <i class="fas fa-info-circle"></i> <?php echo e(session('info')); ?>

        <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
<?php endif; ?>


<main>
    <?php echo $__env->yieldContent('content'); ?>
</main>


<footer class="footer">
    <div class="footer-content">
        <h3>Learnify</h3>
        <p>Transforming education through innovative online learning</p>
        <div class="footer-social">
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        </div>
        <p class="footer-copy">&copy; <?php echo e(date('Y')); ?> Learnify. All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo e(asset('js/app.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\XAMPP\htdocs\learnify-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>