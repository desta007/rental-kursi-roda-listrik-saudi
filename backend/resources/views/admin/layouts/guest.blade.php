<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - MobilityKSA Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: var(--spacing-xl);
        }

        .login-header {
            text-align: center;
            margin-bottom: var(--spacing-xl);
        }

        .login-logo {
            width: 80px;
            height: 80px;
            background: var(--primary);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-lg);
            font-size: 2rem;
            color: white;
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
        }

        .login-title span {
            color: var(--secondary);
        }

        .login-subtitle {
            color: var(--text-muted);
        }

        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: var(--spacing-xl);
        }

        .form-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: var(--spacing-lg);
            text-align: center;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: var(--spacing-lg);
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .remember-check input {
            accent-color: var(--primary);
        }

        .forgot-link {
            font-size: 0.875rem;
            color: var(--primary-light);
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: var(--spacing-md);
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .login-btn:hover {
            background: var(--primary-light);
        }

        .login-footer {
            text-align: center;
            margin-top: var(--spacing-xl);
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: var(--spacing-md);
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .input-group .form-input {
            padding-left: 40px;
        }

        .toggle-password {
            position: absolute;
            right: var(--spacing-md);
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
        }

        .toggle-password:hover {
            color: var(--text-primary);
        }

        .error-message {
            color: var(--error);
            font-size: 0.875rem;
            margin-top: var(--spacing-sm);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid var(--error);
            border-radius: var(--radius-md);
            padding: var(--spacing-md);
            color: var(--error);
            margin-bottom: var(--spacing-md);
            text-align: center;
        }
    </style>
</head>
<body>
    @yield('content')
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>
