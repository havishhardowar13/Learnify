@extends('layouts.app')
@section('title', 'Instructor Dashboard')

@section('content')
<div class="section">
    <div class="dashboard-header">
        <h2>Instructor Dashboard</h2>
        <p>Welcome back, {{ auth()->user()->first_name }}!</p>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card border-primary">
            <h4>My Courses</h4>
            <div class="stat-row">
                <span class="stat-number">{{ $stats['total_courses'] }}</span>
                <i class="fas fa-chalkboard-teacher stat-icon text-primary"></i>
            </div>
        </div>
        <div class="stat-card border-success">
            <h4>Total Students</h4>
            <div class="stat-row">
                <span class="stat-number">{{ $stats['total_students'] }}</span>
                <i class="fas fa-user-graduate stat-icon text-success"></i>
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="card quick-actions">
        <h3>Quick Actions</h3>
        <div class="actions-grid">
            <a href="{{ route('courses.create') }}" class="action-item">
                <i class="fas fa-plus-circle text-primary"></i>
                <span>Create New Course</span>
            </a>
            <a href="{{ route('courses.index') }}" class="action-item">
                <i class="fas fa-book text-success"></i>
                <span>Browse All Courses</span>
            </a>
        </div>
    </div>

    {{-- My courses --}}
    <div class="dashboard-section">
        <div class="dashboard-section-header">
            <h3>My Courses</h3>
            <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Course
            </a>
        </div>

        @if($courses->isNotEmpty())
            <div class="courses-grid">
                @foreach($courses as $course)
                    <div class="course-card">
                        <div class="course-badge {{ $course->is_published ? 'badge-published' : 'badge-draft' }}">
                            {{ $course->is_published ? 'Published' : 'Draft' }}
                        </div>
                        <div class="course-image">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="course-content">
                            <span class="course-category">{{ $course->category }}</span>
                            <h3 class="course-title">{{ $course->title }}</h3>
                            <div class="course-meta-row">
                                <span><i class="fas fa-users"></i> {{ $course->enrollments_count }} students</span>
                            </div>
                            <p class="course-description">{{ Str::limit($course->description, 100) }}</p>
                            <div class="course-footer">
                                <span class="price">${{ number_format($course->price, 2) }}</span>
                                <div style="display:flex;gap:.5rem">
                                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ route('courses.show', $course) }}" class="btn-enroll">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-chalkboard-teacher empty-icon"></i>
                <h3>No Courses Created</h3>
                <p>Share your knowledge by creating your first course.</p>
                <a href="{{ route('courses.create') }}" class="btn btn-primary">Create Course</a>
            </div>
        @endif
    </div>
</div>
@endsection
