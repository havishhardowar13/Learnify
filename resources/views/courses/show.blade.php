@extends('layouts.app')
@section('title', $course->title)

@section('content')
<section class="section">
    <div class="course-detail-grid">

        {{-- Left: Course info --}}
        <div class="course-detail-main">
            <span class="course-category">{{ $course->category }}</span>
            <h1 class="course-detail-title">{{ $course->title }}</h1>

            <div class="course-meta-row">
                <span class="rating">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= round($course->rating) ? '' : '-o' }}"></i>
                    @endfor
                    <strong>{{ $course->rating }}</strong>
                </span>
                <span class="review-count">({{ $course->reviews_count }} reviews)</span>
                <span class="enroll-count">
                    <i class="fas fa-users"></i> {{ $course->enrollments()->count() }} students enrolled
                </span>
            </div>

            <div class="instructor-row">
                <i class="fas fa-user-tie"></i>
                Taught by <strong>{{ $course->instructor?->full_name }}</strong>
            </div>

            @if($course->duration)
                <div class="duration-row">
                    <i class="fas fa-clock"></i> {{ $course->duration }} of content
                </div>
            @endif

            <div class="course-detail-description">
                <h3>About this course</h3>
                <p>{{ $course->description }}</p>
            </div>
        </div>

        {{-- Right: Enroll card --}}
        <div class="course-enroll-card">
            <div class="enroll-price">
                <span class="price">${{ number_format($course->price, 2) }}</span>
                @if($course->original_price && $course->original_price > $course->price)
                    <span class="original-price">${{ number_format($course->original_price, 2) }}</span>
                    <span class="discount-badge">
                        {{ round((1 - $course->price / $course->original_price) * 100) }}% OFF
                    </span>
                @endif
            </div>

            @auth
                @if($isEnrolled)
                    <div class="alert alert-success" style="margin-bottom:1rem">
                        <i class="fas fa-check-circle"></i> You are enrolled in this course.
                    </div>
                    <a href="{{ route('dashboard.student') }}" class="btn btn-primary btn-full">
                        <i class="fas fa-play"></i> Continue Learning
                    </a>
                @elseif(auth()->user()->isStudent())
                    <form method="POST" action="{{ route('courses.enroll', $course) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-full">
                            <i class="fas fa-graduation-cap"></i> Enroll Now
                        </button>
                    </form>
                @elseif(auth()->user()->isInstructor() && auth()->id() === $course->instructor_id)
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline btn-full">
                        <i class="fas fa-edit"></i> Edit Course
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-full">
                    <i class="fas fa-graduation-cap"></i> Enroll Now
                </a>
                <p class="enroll-login-hint">
                    Already have an account? <a href="{{ route('login') }}">Login</a>
                </p>
            @endauth

            <ul class="course-includes">
                <li><i class="fas fa-infinity"></i> Full lifetime access</li>
                <li><i class="fas fa-mobile-alt"></i> Access on mobile and desktop</li>
                <li><i class="fas fa-certificate"></i> Certificate of completion</li>
            </ul>
        </div>

    </div>
</section>
@endsection
