<?php $__env->startSection('title', 'Instructor Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="section">
    <div class="dashboard-header">
        <h2>Instructor Dashboard</h2>
        <p>Welcome back, <?php echo e(auth()->user()->first_name); ?>!</p>
    </div>

    
    <div class="stats-grid">
        <div class="stat-card border-primary">
            <h4>My Courses</h4>
            <div class="stat-row">
                <span class="stat-number"><?php echo e($stats['total_courses']); ?></span>
                <i class="fas fa-chalkboard-teacher stat-icon text-primary"></i>
            </div>
        </div>
        <div class="stat-card border-success">
            <h4>Total Students</h4>
            <div class="stat-row">
                <span class="stat-number"><?php echo e($stats['total_students']); ?></span>
                <i class="fas fa-user-graduate stat-icon text-success"></i>
            </div>
        </div>
    </div>

    
    <div class="card quick-actions">
        <h3>Quick Actions</h3>
        <div class="actions-grid">
            <a href="<?php echo e(route('courses.create')); ?>" class="action-item">
                <i class="fas fa-plus-circle text-primary"></i>
                <span>Create New Course</span>
            </a>
            <a href="<?php echo e(route('courses.index')); ?>" class="action-item">
                <i class="fas fa-book text-success"></i>
                <span>Browse All Courses</span>
            </a>
        </div>
    </div>

    
    <div class="dashboard-section">
        <div class="dashboard-section-header">
            <h3>My Courses</h3>
            <a href="<?php echo e(route('courses.create')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Course
            </a>
        </div>

        <?php if($courses->isNotEmpty()): ?>
            <div class="courses-grid">
                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="course-card">
                        <div class="course-badge <?php echo e($course->is_published ? 'badge-published' : 'badge-draft'); ?>">
                            <?php echo e($course->is_published ? 'Published' : 'Draft'); ?>

                        </div>
                        <div class="course-image">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="course-content">
                            <span class="course-category"><?php echo e($course->category); ?></span>
                            <h3 class="course-title"><?php echo e($course->title); ?></h3>
                            <div class="course-meta-row">
                                <span><i class="fas fa-users"></i> <?php echo e($course->enrollments_count); ?> students</span>
                            </div>
                            <p class="course-description"><?php echo e(Str::limit($course->description, 100)); ?></p>
                            <div class="course-footer">
                                <span class="price">$<?php echo e(number_format($course->price, 2)); ?></span>
                                <div style="display:flex;gap:.5rem">
                                    <a href="<?php echo e(route('courses.edit', $course)); ?>" class="btn btn-outline btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="<?php echo e(route('courses.show', $course)); ?>" class="btn-enroll">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-chalkboard-teacher empty-icon"></i>
                <h3>No Courses Created</h3>
                <p>Share your knowledge by creating your first course.</p>
                <a href="<?php echo e(route('courses.create')); ?>" class="btn btn-primary">Create Course</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMPP\htdocs\learnify-laravel\resources\views/dashboard/instructor.blade.php ENDPATH**/ ?>