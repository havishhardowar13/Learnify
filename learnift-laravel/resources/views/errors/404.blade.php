@extends('layouts.app')
@section('title', '404 – Page Not Found')
@section('content')
<section class="section" style="text-align:center; padding: 6rem 2rem;">
    <div style="font-size:5rem; color:var(--primary); margin-bottom:1rem;">404</div>
    <h2 style="margin-bottom:1rem;">Page Not Found</h2>
    <p style="color:var(--text-light); margin-bottom:2rem;">The page you are looking for does not exist.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Go Home</a>
</section>
@endsection
