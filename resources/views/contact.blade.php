@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')

<section class="hero hero-sm">
    <div class="hero-content">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Get in touch with any questions or feedback.</p>
    </div>
</section>

<div class="section">
    <div class="contact-grid">

        {{-- Contact info --}}
        <div>
            <h2>Get In Touch</h2>
            <div class="contact-items">
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4>Our Location</h4>
                        <p>Réduit, Moka<br>Mauritius</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <h4>Phone Number</h4>
                        <p>+230 5773 7096<br>Mon–Fri: 9:00 AM – 6:00 PM</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h4>Email Address</h4>
                        <p>support@learnify.com<br>info@learnify.com</p>
                    </div>
                </div>
            </div>

            <div class="support-hours">
                <h4>Support Hours</h4>
                <p><strong>Monday – Friday:</strong> 9:00 AM – 6:00 PM MUT</p>
                <p><strong>Saturday:</strong> 10:00 AM – 4:00 PM MUT</p>
                <p><strong>Sunday:</strong> Closed</p>
            </div>
        </div>

        {{-- Contact form --}}
        <div class="card">
            <h2 style="margin-bottom:1.5rem">Send us a Message</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.send') }}" novalidate>
                @csrf

                <div class="form-group">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                        class="form-control @error('name') is-error @enderror"
                        value="{{ old('name') }}" required>
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-error @enderror"
                        value="{{ old('email') }}" required>
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="subject">Subject <span class="required">*</span></label>
                    <input type="text" id="subject" name="subject"
                        class="form-control @error('subject') is-error @enderror"
                        value="{{ old('subject') }}" required>
                    @error('subject')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="message">Message <span class="required">*</span></label>
                    <textarea id="message" name="message" rows="6"
                        class="form-control @error('message') is-error @enderror"
                        required>{{ old('message') }}</textarea>
                    @error('message')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
