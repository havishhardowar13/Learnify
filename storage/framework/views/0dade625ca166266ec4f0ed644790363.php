<?php $__env->startSection('title', 'Learn Skills for Your Future'); ?>

<?php $__env->startSection('content'); ?>


<section class="hero">
    <div class="hero-content fade-in-up">
        <h1>Learn Skills for Your Future</h1>
        <p>Access high-quality courses from expert instructors, earn certificates, and advance your career.</p>
        <div class="hero-buttons">
            <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-white">Explore Courses</a>
            <a href="<?php echo e(route('register')); ?>"       class="btn btn-outline">Start Learning Free</a>
        </div>
    </div>
</section>


<section class="stats-bar">
    <div class="stats-bar-inner">
        <div class="stat-item"><span class="stat-num">50,000+</span><span class="stat-label">Students</span></div>
        <div class="stat-item"><span class="stat-num">200+</span><span class="stat-label">Courses</span></div>
        <div class="stat-item"><span class="stat-num">50+</span><span class="stat-label">Expert Instructors</span></div>
        <div class="stat-item"><span class="stat-num">95%</span><span class="stat-label">Satisfaction Rate</span></div>
    </div>
</section>


<section class="section">
    <div class="section-header">
        <h2>Featured Courses</h2>
        <hr class="divider">
        <p>Discover our most popular courses designed to boost your career</p>
    </div>

    <?php
        $featured = \App\Models\Course::with('instructor')
            ->published()
            ->orderByDesc('reviews_count')
            ->take(4)
            ->get();
    ?>

    <div class="courses-grid">
        <?php $__empty_1 = true; $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('components.course-card', ['course' => $course], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="empty-text">No courses available yet.</p>
        <?php endif; ?>
    </div>

    <div class="section-cta">
        <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-primary">View All Courses</a>
    </div>
</section>


<section class="section features-section">
    <div class="section-header">
        <h2>Why Choose Learnify?</h2>
        <hr class="divider">
        <p>We provide the best learning experience for our students</p>
    </div>
    <div class="features-grid">
        <?php $__currentLoopData = [
            ['fas fa-chalkboard-teacher', 'Expert Instructors',   'Learn from industry experts with years of practical experience.'],
            ['fas fa-certificate',         'Certified Courses',    'Earn recognised certificates to boost your career prospects.'],
            ['fas fa-clock',               'Learn at Your Pace',   'Access course materials anytime, anywhere at your own speed.'],
            ['fas fa-headset',             '24/7 Support',         'Our support team is always here to help you succeed.'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $title, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="feature-card fade-in-up">
            <div class="feature-icon"><i class="<?php echo e($icon); ?>"></i></div>
            <h3><?php echo e($title); ?></h3>
            <p><?php echo e($desc); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>


<section class="cta-banner">
    <div class="cta-inner">
        <h2>Ready to Start Your Learning Journey?</h2>
        <p>Join thousands of students who have transformed their careers with Learnify</p>
        <div class="hero-buttons">
            <a href="<?php echo e(route('register')); ?>"       class="btn btn-white">Get Started Free</a>
            <a href="<?php echo e(route('courses.index')); ?>"  class="btn btn-outline">Browse Courses</a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Scroll-triggered fade-in
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.style.opacity  = '1';
            e.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in-up').forEach(el => {
    el.style.opacity   = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity .6s ease, transform .6s ease';
    observer.observe(el);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMPP\htdocs\learnify-laravel\resources\views/home.blade.php ENDPATH**/ ?>