@extends('layouts.app')
@section('title', '500 – Server Error')
@section('content')
<section class="section" style="text-align:center; padding: 6rem 2rem;">
    <div style="font-size:5rem; color:var(--danger); margin-bottom:1rem;">500</div>
    <h2 style="margin-bottom:1rem;">Server Error</h2>
    <p style="color:var(--text-light); margin-bottom:2rem;">Something went wrong on our end. Please try again later.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Go Home</a>
</section>
@endsection
