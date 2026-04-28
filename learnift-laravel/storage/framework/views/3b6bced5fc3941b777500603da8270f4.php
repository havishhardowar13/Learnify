<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="section">
    <div class="dashboard-header">
        <h2>Admin Dashboard</h2>
        <p>Welcome back, <?php echo e(auth()->user()->first_name); ?>!</p>
    </div>

    
    <div class="stats-grid stats-grid-4">
        <div class="stat-card border-primary">
            <h4>Total Users</h4>
            <div class="stat-row">
                <span class="stat-number"><?php echo e($stats['total_users']); ?></span>
                <i class="fas fa-users stat-icon text-primary"></i>
            </div>
        </div>
        <div class="stat-card border-success">
            <h4>Total Courses</h4>
            <div class="stat-row">
                <span class="stat-number"><?php echo e($stats['total_courses']); ?></span>
                <i class="fas fa-book stat-icon text-success"></i>
            </div>
        </div>
        <div class="stat-card border-info">
            <h4>Total Enrollments</h4>
            <div class="stat-row">
                <span class="stat-number"><?php echo e($stats['total_enrollments']); ?></span>
                <i class="fas fa-user-graduate stat-icon text-info"></i>
            </div>
        </div>
        <div class="stat-card border-warning">
            <h4>Platform Status</h4>
            <div class="stat-row">
                <span class="stat-number text-success" style="font-size:1.2rem">Active</span>
                <i class="fas fa-circle stat-icon text-success"></i>
            </div>
        </div>
    </div>

    
    <div class="card quick-actions">
        <h3>Quick Actions</h3>
        <div class="actions-grid">
            <a href="<?php echo e(route('courses.index')); ?>" class="action-item">
                <i class="fas fa-book text-success"></i>
                <span>Browse Courses</span>
            </a>
            <a href="<?php echo e(route('courses.create')); ?>" class="action-item">
                <i class="fas fa-plus-circle text-primary"></i>
                <span>Create Course</span>
            </a>
        </div>
    </div>

    
    <div class="card">
        <h3 style="margin-bottom:1.5rem">Recent Users</h3>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($user->full_name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                            <span class="role-badge role-<?php echo e($user->role); ?>">
                                <?php echo e(ucfirst($user->role)); ?>

                            </span>
                        </td>
                        <td><?php echo e($user->created_at->format('M d, Y')); ?></td>
                        <td>
                            <span class="status-badge <?php echo e($user->is_active ? 'status-active' : 'status-inactive'); ?>">
                                <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMPP\htdocs\learnify-laravel\resources\views/dashboard/admin.blade.php ENDPATH**/ ?>