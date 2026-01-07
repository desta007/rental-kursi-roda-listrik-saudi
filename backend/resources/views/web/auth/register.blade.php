<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - MobilityKSA</title>

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
            <!-- Header -->
            <div class="register-header">
                <a href="{{ route('web.login') }}" class="back-btn">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="header-title">Create Account</h1>
            </div>

            <!-- Register Form -->
            <div class="auth-form">
                @if($errors->any())
                    <div class="alert alert-danger"
                        style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); border-radius: var(--radius-md); padding: var(--spacing-md); margin-bottom: var(--spacing-md); color: var(--error);">
                        <ul style="margin: 0; padding-left: var(--spacing-md);">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('web.register.submit') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-input" placeholder="Enter your full name"
                            value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
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
                        <label class="form-label">Email (Optional)</label>
                        <input type="email" name="email" class="form-input" placeholder="your@email.com"
                            value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Identity Type *</label>
                        <select name="identity_type" class="form-input" required>
                            <option value="">Select type</option>
                            <option value="national_id" {{ old('identity_type') == 'national_id' ? 'selected' : '' }}>
                                National ID (Saudi)</option>
                            <option value="passport" {{ old('identity_type') == 'passport' ? 'selected' : '' }}>Passport
                            </option>
                            <option value="iqama" {{ old('identity_type') == 'iqama' ? 'selected' : '' }}>Iqama</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Identity Number *</label>
                        <input type="text" name="identity_number" class="form-input" placeholder="Enter ID number"
                            value="{{ old('identity_number') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" placeholder="Minimum 6 characters"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-input"
                            placeholder="Confirm your password" required>
                    </div>

                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" required>
                        <label class="terms-text" for="terms">
                            I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full btn-lg mt-md">
                        <i class="fa-solid fa-user-plus"></i>
                        Create Account
                    </button>
                </form>

                <div class="divider-text">
                    <span>Already have an account?</span>
                </div>

                <a href="{{ route('web.login') }}" class="btn btn-secondary btn-full">
                    <i class="fa-solid fa-sign-in-alt"></i>
                    Sign In
                </a>
            </div>
        </div>
    </div>

    <style>
        .auth-screen {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .register-header {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            background: var(--bg-secondary);
        }

        .back-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-input);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            text-decoration: none;
        }

        .header-title {
            flex: 1;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .auth-form {
            flex: 1;
            padding: var(--spacing-lg);
            overflow-y: auto;
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

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-md);
        }

        .terms-checkbox input {
            width: 20px;
            height: 20px;
            accent-color: var(--primary);
        }

        .terms-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .terms-text a {
            color: var(--primary-light);
            text-decoration: none;
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