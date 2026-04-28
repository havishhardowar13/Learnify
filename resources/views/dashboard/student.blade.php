@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="section">
    <div class="dashboard-header">
        <h2>Welcome back, {{ auth()->user()->first_name }}!</h2>
        <p>Continue your learning journey</p>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card border-primary">
            <h4>Enrolled Courses</h4>
            <div class="stat-row">
                <span class="stat-number">{{ $stats['enrolled_courses'] }}</span>
                <i class="fas fa-book stat-icon text-primary"></i>
            </div>
        </div>
        <div class="stat-card border-success">
            <h4>Completed</h4>
            <div class="stat-row">
                <span class="stat-number">{{ $stats['completed_courses'] }}</span>
                <i class="fas fa-check-circle stat-icon text-success"></i>
            </div>
        </div>
        <div class="stat-card border-warning">
            <h4>In Progress</h4>
            <div class="stat-row">
                <span class="stat-number">{{ $stats['in_progress'] }}</span>
                <i class="fas fa-spinner stat-icon text-warning"></i>
            </div>
        </div>
    </div>

    {{-- Enrolled courses --}}
    <div class="dashboard-section">
        <div class="dashboard-section-header">
            <h3>Your Courses</h3>
            <a href="{{ route('courses.index') }}" class="btn btn-outline btn-sm">Browse More</a>
        </div>

        @if($enrollments->isNotEmpty())
            <div class="courses-grid">
                @foreach($enrollments as $enrollment)
                    <div class="course-card">
                        <div class="course-badge badge-{{ $enrollment->completion_status }}">
                            {{ ucfirst(str_replace('_', ' ', $enrollment->completion_status)) }}
                        </div>
                        <div class="course-image">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title">{{ $enrollment->course->title }}</h3>
                            <p class="course-description">
                                {{ Str::limit($enrollment->course->description, 100) }}
                            </p>

                            {{-- Progress bar --}}
                            <div class="progress-wrap">
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ $enrollment->progress }}%"></div>
                                </div>
                                <span class="progress-label">{{ $enrollment->progress }}% complete</span>
                            </div>

                            <div class="course-footer">
                                <span class="enrolled-date">
                                    <i class="fas fa-calendar"></i>
                                    Enrolled {{ $enrollment->enrolled_at?->format('M d, Y') }}
                                </span>
                                <a href="{{ route('courses.show', $enrollment->course) }}" class="btn-enroll">
                                    Continue
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-book empty-icon"></i>
                <h3>No Courses Yet</h3>
                <p>Start your learning journey by enrolling in a course.</p>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">Browse Courses</a>
            </div>
        @endif
    </div>
</div>
@endsection
