@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<section class="auth-section">
    <div class="auth-card" style="max-width:520px">
        <div class="auth-header">
            <div class="auth-icon"><i class="fas fa-user-plus"></i></div>
            <h2>Create Your Account</h2>
            <p>Join Learnify and start your learning journey today</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="form-row-2">
                <div class="form-group">
                    <label for="first_name">First Name <span class="required">*</span></label>
                    <input type="text" id="first_name" name="first_name"
                        class="form-control @error('first_name') is-error @enderror"
                        value="{{ old('first_name') }}" required autofocus>
                    @error('first_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name <span class="required">*</span></label>
                    <input type="text" id="last_name" name="last_name"
                        class="form-control @error('last_name') is-error @enderror"
                        value="{{ old('last_name') }}" required>
                    @error('last_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <div class="input-icon-wrap">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-error @enderror"
                        value="{{ old('email') }}" autocomplete="email" required>
                </div>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            {{-- Role selector --}}
            <div class="form-group">
                <label>I want to join as <span class="required">*</span></label>
                <div class="role-options">
                    <label class="role-option {{ old('role', 'student') === 'student' ? 'selected' : '' }}">
                        <input type="radio" name="role" value="student"
                            {{ old('role', 'student') === 'student' ? 'checked' : '' }}>
                        <i class="fas fa-user-graduate role-icon student-icon"></i>
                        <strong>Student</strong>
                        <span class="role-desc">Enroll in courses and learn new skills</span>
                    </label>
                    <label class="role-option {{ old('role') === 'instructor' ? 'selected' : '' }}">
                        <input type="radio" name="role" value="instructor"
                            {{ old('role') === 'instructor' ? 'checked' : '' }}>
                        <i class="fas fa-chalkboard-teacher role-icon instructor-icon"></i>
                        <strong>Instructor</strong>
                        <span class="role-desc">Create and teach courses</span>
                    </label>
                </div>
                @error('role')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <div class="input-icon-wrap">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-error @enderror"
                        autocomplete="new-password" required>
                </div>
                <span class="field-hint">Min. 8 characters with uppercase and numbers</span>
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                <div class="input-icon-wrap">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control" autocomplete="new-password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <p class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </p>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.role-option input').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.role-option').forEach(o => o.classList.remove('selected'));
        radio.closest('.role-option').classList.add('selected');
    });
});
</script>
@endpush
