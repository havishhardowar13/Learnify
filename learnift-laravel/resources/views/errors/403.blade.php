@extends('layouts.app')
@section('title', '403 – Forbidden')
@section('content')
<section class="section" style="text-align:center; padding: 6rem 2rem;">
    <div style="font-size:5rem; color:var(--danger); margin-bottom:1rem;">403</div>
    <h2 style="margin-bottom:1rem;">Access Denied</h2>
    <p style="color:var(--text-light); margin-bottom:2rem;">You do not have permission to view this page.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Go Home</a>
</section>
@endsection
