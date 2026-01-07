<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - MobilityKSA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
</head>

<body>
    <div class="mobile-container">
        <div class="screen auth-screen">
            <!-- Logo Section -->
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fa-solid fa-wheelchair"></i>
                </div>
                <h1 class="auth-title">Mobility<span>KSA</span></h1>
                <p class="auth-subtitle">Electric Wheelchair Rental</p>
            </div>

            <!-- Login Form -->
            <div class="auth-form">
                <h2 class="form-title">Welcome Back</h2>
                <p class="form-subtitle">Sign in to continue</p>

                @if($errors->any())
                    <div class="alert alert-danger"
                        style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); border-radius: var(--radius-md); padding: var(--spacing-md); margin-bottom: var(--spacing-md); color: var(--error);">
                        @foreach($errors->all() as $error)
                            <p style="margin: 0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('web.login.submit') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <div class="phone-input-wrapper">
                            <select name="country_code" class="form-input country-code-select">
                                <option value="+966" {{ old('country_code', '+966') == '+966' ? 'selected' : '' }}>🇸🇦
                                    +966</option>
                                <option value="+971" {{ old('country_code') == '+971' ? 'selected' : '' }}>🇦🇪 +971
                                </option>
                                <option value="+973" {{ old('country_code') == '+973' ? 'selected' : '' }}>🇧🇭 +973
                                </option>
                                <option value="+974" {{ old('country_code') == '+974' ? 'selected' : '' }}>🇶🇦 +974
                                </option>
                                <option value="+968" {{ old('country_code') == '+968' ? 'selected' : '' }}>🇴🇲 +968
                                </option>
                                <option value="+965" {{ old('country_code') == '+965' ? 'selected' : '' }}>🇰🇼 +965
                                </option>
                                <option value="+20" {{ old('country_code') == '+20' ? 'selected' : '' }}>🇪🇬 +20</option>
                                <option value="+962" {{ old('country_code') == '+962' ? 'selected' : '' }}>🇯🇴 +962
                                </option>
                                <option value="+961" {{ old('country_code') == '+961' ? 'selected' : '' }}>🇱🇧 +961
                                </option>
                                <option value="+90" {{ old('country_code') == '+90' ? 'selected' : '' }}>🇹🇷 +90</option>
                                <option value="+92" {{ old('country_code') == '+92' ? 'selected' : '' }}>🇵🇰 +92</option>
                                <option value="+91" {{ old('country_code') == '+91' ? 'selected' : '' }}>🇮🇳 +91</option>
                                <option value="+62" {{ old('country_code') == '+62' ? 'selected' : '' }}>🇮🇩 +62</option>
                                <option value="+60" {{ old('country_code') == '+60' ? 'selected' : '' }}>🇲🇾 +60</option>
                                <option value="+44" {{ old('country_code') == '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                                <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                            </select>
                            <input type="text" name="phone" class="form-input phone-number" placeholder="5XXXXXXXX"
                                value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Enter your password"
                            required>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" value="1">
                            <span>Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full btn-lg">
                        <i class="fa-solid fa-sign-in-alt"></i>
                        Sign In
                    </button>
                </form>

                <div class="divider-text">
                    <span>Don't have an account?</span>
                </div>

                <a href="{{ route('web.register') }}" class="btn btn-secondary btn-full">
                    <i class="fa-solid fa-user-plus"></i>
                    Create Account
                </a>

                <a href="{{ route('home') }}" class="btn btn-outline btn-full mt-md">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>

    <style>
        .auth-screen {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: var(--spacing-lg);
            padding-bottom: 0;
        }

        .auth-header {
            text-align: center;
            padding: var(--spacing-2xl) 0;
        }

        .auth-logo {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-md);
            font-size: 2.5rem;
            color: white;
        }

        .auth-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
        }

        .auth-title span {
            color: var(--secondary);
        }

        .auth-subtitle {
            color: var(--text-muted);
        }

        .auth-form {
            flex: 1;
            background: var(--bg-card);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            padding: var(--spacing-lg);
            margin: 0 calc(var(--spacing-lg) * -1);
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
        }

        .form-subtitle {
            color: var(--text-muted);
            margin-bottom: var(--spacing-lg);
        }

        .phone-input-wrapper {
            display: flex;
            gap: var(--spacing-sm);
        }

        .country-code-select {
            width: 110px;
            flex-shrink: 0;
            padding: var(--spacing-sm) var(--spacing-sm);
        }

        .phone-number {
            flex: 1;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-lg);
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-size: 0.875rem;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .remember-me input {
            accent-color: var(--primary);
        }

        .divider-text {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            color: var(--text-muted);
            font-size: 0.875rem;
            margin: var(--spacing-lg) 0;
        }

        .divider-text::before,
        .divider-text::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
    </style>
</body>

</html>