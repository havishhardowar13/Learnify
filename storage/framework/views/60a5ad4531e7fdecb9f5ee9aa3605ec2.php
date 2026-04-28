


<div class="course-card">
    <?php if($course->is_published): ?>
        <div class="course-badge"><?php echo e($course->category ?? 'Course'); ?></div>
    <?php else: ?>
        <div class="course-badge badge-draft">Draft</div>
    <?php endif; ?>

    <div class="course-image">
        <?php if($course->thumbnail_url): ?>
            <img src="<?php echo e($course->thumbnail_url); ?>" alt="<?php echo e($course->title); ?>">
        <?php else: ?>
            <i class="<?php echo e(match($course->category ?? '') {
                'Web Development' => 'fas fa-code',
                'Design'          => 'fas fa-palette',
                'Data Science'    => 'fas fa-chart-line',
                'Marketing'       => 'fas fa-bullseye',
                'Cybersecurity'   => 'fas fa-shield-alt',
                default           => 'fas fa-book'
            }); ?>"></i>
        <?php endif; ?>
    </div>

    <div class="course-content">
        <span class="course-category"><?php echo e($course->category); ?></span>
        <h3 class="course-title"><?php echo e($course->title); ?></h3>

        <div class="course-meta">
            <span class="rating">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star<?php echo e($i <= round($course->rating) ? '' : '-o'); ?>"></i>
                <?php endfor; ?>
            </span>
            <span class="review-count">(<?php echo e($course->reviews_count); ?> reviews)</span>
        </div>

        <p class="course-description"><?php echo e(Str::limit($course->description, 110)); ?></p>

        <div class="course-instructor">
            <i class="fas fa-user-tie"></i>
            <?php echo e($course->instructor?->full_name ?? 'Learnify Instructor'); ?>

        </div>

        <div class="course-footer">
            <div class="course-price">
                <span class="price">$<?php echo e(number_format($course->price, 2)); ?></span>
                <?php if($course->original_price && $course->original_price > $course->price): ?>
                    <span class="original-price">$<?php echo e(number_format($course->original_price, 2)); ?></span>
                <?php endif; ?>
            </div>
            <a href="<?php echo e(route('courses.show', $course)); ?>" class="btn-enroll">View Course</a>
        </div>
    </div>
</div>
<?php /**PATH D:\XAMPP\htdocs\learnify-laravel\resources\views/components/course-card.blade.php ENDPATH**/ ?>