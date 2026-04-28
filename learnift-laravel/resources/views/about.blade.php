@extends('layouts.app')
@section('title', 'About Us')

@section('content')

<section class="hero hero-sm">
    <div class="hero-content">
        <h1>About Learnify</h1>
        <p>Transforming education through innovative online learning solutions</p>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2>Our Mission</h2>
        <hr class="divider">
    </div>
    <div class="text-center-block">
        <p class="lead">
            At Learnify, we believe that education should be accessible, engaging, and transformative.
            Our mission is to empower learners worldwide with high-quality courses taught by industry experts,
            helping them acquire the skills needed to thrive in today's competitive job market.
        </p>
    </div>
</section>

<section class="section features-section">
    <div class="section-header">
        <h2>Why Choose Learnify?</h2>
        <hr class="divider">
    </div>
    <div class="features-grid">
        @foreach([
            ['fas fa-users',             '50,000+ Students',   'Join our growing community of learners from around the world.'],
            ['fas fa-book-open',          '200+ Courses',       'Comprehensive curriculum covering in-demand skills and technologies.'],
            ['fas fa-chalkboard-teacher', 'Expert Instructors', 'Learn from industry professionals with real-world experience.'],
            ['fas fa-graduation-cap',     'Career Support',     'Get help with job placement and career advancement.'],
        ] as [$icon, $title, $desc])
        <div class="feature-card">
            <div class="feature-icon"><i class="{{ $icon }}"></i></div>
            <h3>{{ $title }}</h3>
            <p>{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2>Meet Our Team</h2>
        <hr class="divider">
        <p>The passionate students behind Learnify</p>
    </div>
    <div class="team-grid">
        @foreach([
            ['Havish',  'Cybersecurity Student', 'Passionate about educational technology and creating accessible learning platforms.'],
            ['Julien',  'Cybersecurity Student', 'Focused on curriculum development and creating engaging learning experiences.'],
            ['CJ',      'Cybersecurity Student', 'Combines technical expertise with a vision for accessible education.'],
            ['Daniel',  'Cybersecurity Student', 'Creates intuitive user interfaces to enhance the learning experience.'],
            ['Jibran',  'Cybersecurity Student', 'Manages community engagement and strategic partnerships.'],
        ] as [$name, $role, $bio])
        <div class="team-card">
            <div class="team-avatar"><i class="fas fa-user"></i></div>
            <h3>{{ $name }}</h3>
            <p class="team-role">{{ $role }}</p>
            <p class="team-bio">{{ $bio }}</p>
        </div>
        @endforeach
    </div>
</section>

<section class="cta-banner">
    <div class="cta-inner">
        <h2>Ready to Start Your Learning Journey?</h2>
        <p>Join thousands of students who have transformed their careers with Learnify</p>
        <div class="hero-buttons">
            <a href="{{ route('register') }}"      class="btn btn-white">Get Started</a>
            <a href="{{ route('courses.index') }}" class="btn btn-outline">Browse Courses</a>
        </div>
    </div>
</section>

@endsection
