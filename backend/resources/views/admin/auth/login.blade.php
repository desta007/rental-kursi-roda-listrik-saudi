@extends('admin.layouts.guest')

@section('title', 'Login')

@section('content')
<div class="login-page">
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <i class="fa-solid fa-wheelchair"></i>
            </div>
            <h1 class="login-title">Mobility<span>KSA</span></h1>
            <p class="login-subtitle">Admin Dashboard</p>
        </div>

        <div class="login-card">
            <h2 class="form-title">Sign In</h2>

            @if($errors->any())
                <div class="alert-error">
                    <i class="fa-solid fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <i class="fa-solid fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-input" 
                               placeholder="admin@mobilityksa.com"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" name="password" id="password" class="form-input" 
                               placeholder="Enter your password" required>
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="remember-row">
                    <label class="remember-check">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Sign In
                </button>
            </form>
        </div>

        <div class="login-footer">
            &copy; {{ date('Y') }} MobilityKSA. All rights reserved.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endpush
