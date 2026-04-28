<?php $__env->startSection('title', 'My Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="section">
    <div class="dashboard-header">
        <h2>Welcome back, <?php echo e(auth()->user()->first_name); ?>!</h2>
        <p>Continue your learning journey</p>
    </div>

    
    <div class="stats-grid">
        <div class="stat-card border-primary">
            <h4>Enrolled Courses</h4>
            <div class="stat-row">
                <span class="stat-number"><?php echo e($stats['enrolled_courses']); ?></span>
                <i class="fas fa-book stat-icon text-primary"></i>
            </div>
        </div>
        <div class="stat-card border-success">
            <h4>Completed</h4>
            <div class="stat-row">
                <span class="stat-number"><?php echo e($stats['completed_courses']); ?></span>
                <i class="fas fa-check-circle stat-icon text-success"></i>
            </div>
        </div>
        <div class="stat-card border-warning">
            <h4>In Progress</h4>
            <div class="stat-row">
                <span class="stat-number"><?php echo e($stats['in_progress']); ?></span>
                <i class="fas fa-spinner stat-icon text-warning"></i>
            </div>
        </div>
    </div>

    
    <div class="dashboard-section">
        <div class="dashboard-section-header">
            <h3>Your Courses</h3>
            <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-outline btn-sm">Browse More</a>
        </div>

        <?php if($enrollments->isNotEmpty()): ?>
            <div class="courses-grid">
                <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="course-card">
                        <div class="course-badge badge-<?php echo e($enrollment->completion_status); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $enrollment->completion_status))); ?>

                        </div>
                        <div class="course-image">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title"><?php echo e($enrollment->course->title); ?></h3>
                            <p class="course-description">
                                <?php echo e(Str::limit($enrollment->course->description, 100)); ?>

                            </p>

                            
                            <div class="progress-wrap">
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: <?php echo e($enrollment->progress); ?>%"></div>
                                </div>
                                <span class="progress-label"><?php echo e($enrollment->progress); ?>% complete</span>
                            </div>

                            <div class="course-footer">
                                <span class="enrolled-date">
                                    <i class="fas fa-calendar"></i>
                                    Enrolled <?php echo e($enrollment->enrolled_at?->format('M d, Y')); ?>

                                </span>
                                <a href="<?php echo e(route('courses.show', $enrollment->course)); ?>" class="btn-enroll">
                                    Continue
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-book empty-icon"></i>
                <h3>No Courses Yet</h3>
                <p>Start your learning journey by enrolling in a course.</p>
                <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-primary">Browse Courses</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMPP\htdocs\learnify-laravel\resources\views/dashboard/student.blade.php ENDPATH**/ ?>