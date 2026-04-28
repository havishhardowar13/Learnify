<?php $__env->startSection('title', $course->title); ?>

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="course-detail-grid">

        
        <div class="course-detail-main">
            <span class="course-category"><?php echo e($course->category); ?></span>
            <h1 class="course-detail-title"><?php echo e($course->title); ?></h1>

            <div class="course-meta-row">
                <span class="rating">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star<?php echo e($i <= round($course->rating) ? '' : '-o'); ?>"></i>
                    <?php endfor; ?>
                    <strong><?php echo e($course->rating); ?></strong>
                </span>
                <span class="review-count">(<?php echo e($course->reviews_count); ?> reviews)</span>
                <span class="enroll-count">
                    <i class="fas fa-users"></i> <?php echo e($course->enrollments()->count()); ?> students enrolled
                </span>
            </div>

            <div class="instructor-row">
                <i class="fas fa-user-tie"></i>
                Taught by <strong><?php echo e($course->instructor?->full_name); ?></strong>
            </div>

            <?php if($course->duration): ?>
                <div class="duration-row">
                    <i class="fas fa-clock"></i> <?php echo e($course->duration); ?> of content
                </div>
            <?php endif; ?>

            <div class="course-detail-description">
                <h3>About this course</h3>
                <p><?php echo e($course->description); ?></p>
            </div>
        </div>

        
        <div class="course-enroll-card">
            <div class="enroll-price">
                <span class="price">$<?php echo e(number_format($course->price, 2)); ?></span>
                <?php if($course->original_price && $course->original_price > $course->price): ?>
                    <span class="original-price">$<?php echo e(number_format($course->original_price, 2)); ?></span>
                    <span class="discount-badge">
                        <?php echo e(round((1 - $course->price / $course->original_price) * 100)); ?>% OFF
                    </span>
                <?php endif; ?>
            </div>

            <?php if(auth()->guard()->check()): ?>
                <?php if($isEnrolled): ?>
                    <div class="alert alert-success" style="margin-bottom:1rem">
                        <i class="fas fa-check-circle"></i> You are enrolled in this course.
                    </div>
                    <a href="<?php echo e(route('dashboard.student')); ?>" class="btn btn-primary btn-full">
                        <i class="fas fa-play"></i> Continue Learning
                    </a>
                <?php elseif(auth()->user()->isStudent()): ?>
                    <form method="POST" action="<?php echo e(route('courses.enroll', $course)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary btn-full">
                            <i class="fas fa-graduation-cap"></i> Enroll Now
                        </button>
                    </form>
                <?php elseif(auth()->user()->isInstructor() && auth()->id() === $course->instructor_id): ?>
                    <a href="<?php echo e(route('courses.edit', $course)); ?>" class="btn btn-outline btn-full">
                        <i class="fas fa-edit"></i> Edit Course
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-full">
                    <i class="fas fa-graduation-cap"></i> Enroll Now
                </a>
                <p class="enroll-login-hint">
                    Already have an account? <a href="<?php echo e(route('login')); ?>">Login</a>
                </p>
            <?php endif; ?>

            <ul class="course-includes">
                <li><i class="fas fa-infinity"></i> Full lifetime access</li>
                <li><i class="fas fa-mobile-alt"></i> Access on mobile and desktop</li>
                <li><i class="fas fa-certificate"></i> Certificate of completion</li>
            </ul>
        </div>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMPP\htdocs\learnify-laravel\resources\views/courses/show.blade.php ENDPATH**/ ?>