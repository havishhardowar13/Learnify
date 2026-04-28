<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Learnify') – Learnify</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- App styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

{{-- ── Navigation ──────────────────────────────────────────────────────────── --}}
<header class="header">
    <nav class="navbar">
        <a href="{{ route('home') }}" class="logo">Learnify</a>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}"         class="{{ request()->routeIs('home')    ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses*') ? 'active' : '' }}">Courses</a></li>
            <li><a href="{{ route('about') }}"         class="{{ request()->routeIs('about')   ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route('contact') }}"       class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>

            @auth
                @if(auth()->user()->isAdmin())
                    <li><a href="{{ route('dashboard.admin') }}">Admin Dashboard</a></li>
                @elseif(auth()->user()->isInstructor())
                    <li><a href="{{ route('dashboard.instructor') }}">My Dashboard</a></li>
                @else
                    <li><a href="{{ route('dashboard.student') }}">My Dashboard</a></li>
                @endif
            @endauth
        </ul>

        <div class="auth-buttons">
            @auth
                <span class="user-greeting">
                    <i class="fas fa-user"></i>
                    {{ auth()->user()->first_name }}
                    <span class="role-badge role-{{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-outline">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}"    class="btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
            @endauth
        </div>
    </nav>
</header>

{{-- ── Flash messages ───────────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="flash flash-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif
@if(session('error'))
    <div class="flash flash-error">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif
@if(session('info'))
    <div class="flash flash-info">
        <i class="fas fa-info-circle"></i> {{ session('info') }}
        <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

{{-- ── Main content ─────────────────────────────────────────────────────────── --}}
<main>
    @yield('content')
</main>

{{-- ── Footer ───────────────────────────────────────────────────────────────── --}}
<footer class="footer">
    <div class="footer-content">
        <h3>Learnify</h3>
        <p>Transforming education through innovative online learning</p>
        <div class="footer-social">
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        </div>
        <p class="footer-copy">&copy; {{ date('Y') }} Learnify. All rights reserved.</p>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
