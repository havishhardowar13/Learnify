{{-- resources/views/components/course-card.blade.php --}}
{{-- Usage: @include('components.course-card', ['course' => $course]) --}}

<div class="course-card">
    @if($course->is_published)
        <div class="course-badge">{{ $course->category ?? 'Course' }}</div>
    @else
        <div class="course-badge badge-draft">Draft</div>
    @endif

    <div class="course-image">
        @if($course->thumbnail_url)
            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}">
        @else
            <i class="{{ match($course->category ?? '') {
                'Web Development' => 'fas fa-code',
                'Design'          => 'fas fa-palette',
                'Data Science'    => 'fas fa-chart-line',
                'Marketing'       => 'fas fa-bullseye',
                'Cybersecurity'   => 'fas fa-shield-alt',
                default           => 'fas fa-book'
            } }}"></i>
        @endif
    </div>

    <div class="course-content">
        <span class="course-category">{{ $course->category }}</span>
        <h3 class="course-title">{{ $course->title }}</h3>

        <div class="course-meta">
            <span class="rating">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star{{ $i <= round($course->rating) ? '' : '-o' }}"></i>
                @endfor
            </span>
            <span class="review-count">({{ $course->reviews_count }} reviews)</span>
        </div>

        <p class="course-description">{{ Str::limit($course->description, 110) }}</p>

        <div class="course-instructor">
            <i class="fas fa-user-tie"></i>
            {{ $course->instructor?->full_name ?? 'Learnify Instructor' }}
        </div>

        <div class="course-footer">
            <div class="course-price">
                <span class="price">${{ number_format($course->price, 2) }}</span>
                @if($course->original_price && $course->original_price > $course->price)
                    <span class="original-price">${{ number_format($course->original_price, 2) }}</span>
                @endif
            </div>
            <a href="{{ route('courses.show', $course) }}" class="btn-enroll">View Course</a>
        </div>
    </div>
</div>
