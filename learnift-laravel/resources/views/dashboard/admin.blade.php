@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="section">
    <div class="dashboard-header">
        <h2>Admin Dashboard</h2>
        <p>Welcome back, {{ auth()->user()->first_name }}!</p>
    </div>

    {{-- Stats --}}
    <div class="stats-grid stats-grid-4">
        <div class="stat-card border-primary">
            <h4>Total Users</h4>
            <div class="stat-row">
                <span class="stat-number">{{ $stats['total_users'] }}</span>
                <i class="fas fa-users stat-icon text-primary"></i>
            </div>
        </div>
        <div class="stat-card border-success">
            <h4>Total Courses</h4>
            <div class="stat-row">
                <span class="stat-number">{{ $stats['total_courses'] }}</span>
                <i class="fas fa-book stat-icon text-success"></i>
            </div>
        </div>
        <div class="stat-card border-info">
            <h4>Total Enrollments</h4>
            <div class="stat-row">
                <span class="stat-number">{{ $stats['total_enrollments'] }}</span>
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

    {{-- Quick actions --}}
    <div class="card quick-actions">
        <h3>Quick Actions</h3>
        <div class="actions-grid">
            <a href="{{ route('courses.index') }}" class="action-item">
                <i class="fas fa-book text-success"></i>
                <span>Browse Courses</span>
            </a>
            <a href="{{ route('courses.create') }}" class="action-item">
                <i class="fas fa-plus-circle text-primary"></i>
                <span>Create Course</span>
            </a>
        </div>
    </div>

    {{-- Recent users --}}
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
                    @foreach($recentUsers as $user)
                    <tr>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge role-{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
