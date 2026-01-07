@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="screen-header">
        <h1 style="font-size: 1.5rem; font-weight: 700;">Profile</h1>
    </div>

    <div class="screen-content">
        @if(session('success'))
            <div class="alert alert-success mb-md"
                style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--success); border-radius: var(--radius-md); padding: var(--spacing-md); color: var(--success);">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <h2 class="profile-name">{{ $user->name }}</h2>
            <p class="profile-phone">{{ $user->phone }}</p>
            @if($user->verification_status == 'verified')
                <span class="verified-badge"><i class="fa-solid fa-check-circle"></i> Verified</span>
            @endif
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $totalBookings }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $activeBookings }}</div>
                <div class="stat-label">Active</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $completedBookings }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <!-- Profile Menu -->
        <div class="profile-menu">
            <a href="{{ route('profile.edit') }}" class="list-item">
                <div class="list-item-icon">
                    <i class="fa-solid fa-user-edit"></i>
                </div>
                <div class="list-item-content">
                    <div class="list-item-title">Edit Profile</div>
                    <div class="list-item-subtitle">Update your information</div>
                </div>
                <i class="fa-solid fa-chevron-right list-item-arrow"></i>
            </a>

            <a href="{{ route('bookings.index') }}" class="list-item">
                <div class="list-item-icon">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div class="list-item-content">
                    <div class="list-item-title">My Bookings</div>
                    <div class="list-item-subtitle">View booking history</div>
                </div>
                <i class="fa-solid fa-chevron-right list-item-arrow"></i>
            </a>

            <div class="list-item">
                <div class="list-item-icon">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div class="list-item-content">
                    <div class="list-item-title">Language</div>
                    <div class="list-item-subtitle">{{ $user->language == 'ar' ? 'العربية' : 'English' }}</div>
                </div>
                <i class="fa-solid fa-chevron-right list-item-arrow"></i>
            </div>

            <div class="list-item">
                <div class="list-item-icon">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div class="list-item-content">
                    <div class="list-item-title">Notifications</div>
                    <div class="list-item-subtitle">Manage notifications</div>
                </div>
                <i class="fa-solid fa-chevron-right list-item-arrow"></i>
            </div>

            <div class="list-item">
                <div class="list-item-icon">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div class="list-item-content">
                    <div class="list-item-title">Help & Support</div>
                    <div class="list-item-subtitle">Get help from our team</div>
                </div>
                <i class="fa-solid fa-chevron-right list-item-arrow"></i>
            </div>

            <div class="list-item">
                <div class="list-item-icon">
                    <i class="fa-solid fa-file-contract"></i>
                </div>
                <div class="list-item-content">
                    <div class="list-item-title">Terms & Conditions</div>
                    <div class="list-item-subtitle">Read our policies</div>
                </div>
                <i class="fa-solid fa-chevron-right list-item-arrow"></i>
            </div>
        </div>

        <!-- Logout -->
        <form action="{{ route('web.logout') }}" method="POST" style="margin-top: var(--spacing-lg);">
            @csrf
            <button type="submit" class="btn btn-secondary btn-full">
                <i class="fa-solid fa-sign-out-alt"></i>
                Logout
            </button>
        </form>

        <p class="version-text">Version 1.0.0</p>
    </div>
@endsection

@push('styles')
    <style>
        .profile-header {
            text-align: center;
            padding: var(--spacing-lg) 0;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--gradient-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--bg-primary);
            margin: 0 auto var(--spacing-md);
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
        }

        .profile-phone {
            color: var(--text-muted);
            margin-bottom: var(--spacing-sm);
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: 4px 12px;
            background: rgba(16, 185, 129, 0.2);
            border-radius: var(--radius-full);
            color: var(--success);
            font-size: 0.875rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .stat-card {
            text-align: center;
            padding: var(--spacing-md);
            background: var(--bg-card);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-light);
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .profile-menu {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .profile-menu .list-item {
            border-radius: 0;
            margin-bottom: 0;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            color: inherit;
        }

        .profile-menu .list-item:last-child {
            border-bottom: none;
        }

        .version-text {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.75rem;
            margin-top: var(--spacing-lg);
        }
    </style>
@endpush