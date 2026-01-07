@extends('layouts.app')

@section('title', $wheelchair->wheelchairType->name)

@section('content')
    <!-- Header -->
    <div class="detail-header">
        <a href="{{ route('wheelchairs.index') }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="header-title">Wheelchair Details</h1>
        <div class="favorite-btn-header" onclick="this.classList.toggle('active')">
            <i class="fa-solid fa-heart"></i>
        </div>
    </div>

    <div class="screen-content detail-content">
        <!-- Image Gallery -->
        <div class="image-gallery">
            @if($wheelchair->wheelchairType->image)
                <img src="{{ asset('storage/' . $wheelchair->wheelchairType->image) }}"
                    alt="{{ $wheelchair->wheelchairType->name }}">
            @else
                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=600&h=400&fit=crop" alt="Wheelchair">
            @endif
            <span class="status-badge status-available gallery-badge">Available</span>
        </div>

        <!-- Wheelchair Info -->
        <div class="wheelchair-detail-info">
            <div class="detail-top">
                <div>
                    <h1 class="detail-name">{{ $wheelchair->wheelchairType->name }}</h1>
                    <p class="detail-code">
                        <i class="fa-solid fa-hashtag"></i>
                        {{ $wheelchair->code }}
                    </p>
                    <p class="detail-brand">
                        <i class="fa-solid fa-industry"></i>
                        {{ $wheelchair->brand ?? 'N/A' }} - {{ $wheelchair->model ?? 'N/A' }}
                    </p>
                    <p class="detail-location">
                        <i class="fa-solid fa-location-dot"></i>
                        {{ $wheelchair->station->name }}
                    </p>
                </div>
                <div class="detail-price">
                    <span class="price-amount">SAR {{ number_format($wheelchair->wheelchairType->daily_rate, 0) }}</span>
                    <span class="price-unit">/ day</span>
                </div>
            </div>

            <!-- Specs -->
            <div class="specs-grid">
                <div class="spec-card">
                    <i class="fa-solid fa-battery-full"></i>
                    <span class="spec-value">{{ $wheelchair->wheelchairType->battery_range_km }}km</span>
                    <span class="spec-label">Range</span>
                </div>
                <div class="spec-card">
                    <i class="fa-solid fa-weight-hanging"></i>
                    <span class="spec-value">{{ $wheelchair->wheelchairType->max_weight_kg }}kg</span>
                    <span class="spec-label">Max Weight</span>
                </div>
                <div class="spec-card">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="spec-value">{{ $wheelchair->wheelchairType->max_speed_kmh }}km/h</span>
                    <span class="spec-label">Speed</span>
                </div>
                <div class="spec-card">
                    <i class="fa-solid fa-clock"></i>
                    <span class="spec-value">4hrs</span>
                    <span class="spec-label">Charge Time</span>
                </div>
            </div>

            <!-- Description -->
            <div class="detail-section">
                <h3 class="section-title">Description</h3>
                <p class="detail-description">
                    {{ $wheelchair->wheelchairType->description ?? 'Premium electric wheelchair designed for comfort and ease of use. Perfect for pilgrims and visitors who need mobility assistance.' }}
                </p>
            </div>

            <!-- Features -->
            @php
                $features = $wheelchair->wheelchairType->features;
                if (is_string($features)) {
                    $features = json_decode($features, true);
                }
            @endphp
            @if($features && is_array($features) && count($features) > 0)
                <div class="detail-section">
                    <h3 class="section-title">Features</h3>
                    <div class="features-list">
                        @foreach($features as $feature)
                            <div class="feature-item">
                                <i class="fa-solid fa-check-circle"></i>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="detail-section">
                    <h3 class="section-title">Features</h3>
                    <div class="features-list">
                        <div class="feature-item">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>Foldable Design</span>
                        </div>
                        <div class="feature-item">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>Joystick Control</span>
                        </div>
                        <div class="feature-item">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>Anti-tip Wheels</span>
                        </div>
                        <div class="feature-item">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>Adjustable Armrests</span>
                        </div>
                        <div class="feature-item">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>LED Lights</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pricing -->
            <div class="detail-section">
                <h3 class="section-title">Pricing</h3>
                <div class="pricing-table">
                    <div class="pricing-row">
                        <span>Daily Rate</span>
                        <span class="price-gold">SAR {{ number_format($wheelchair->wheelchairType->daily_rate, 0) }}</span>
                    </div>
                    <div class="pricing-row">
                        <span>Weekly Rate (7 days)</span>
                        <span class="price-gold">SAR {{ number_format($wheelchair->wheelchairType->weekly_rate, 0) }}</span>
                    </div>
                    <div class="pricing-row">
                        <span>Monthly Rate (30 days)</span>
                        <span class="price-gold">SAR
                            {{ number_format($wheelchair->wheelchairType->monthly_rate, 0) }}</span>
                    </div>
                    <div class="pricing-row">
                        <span>Refundable Deposit</span>
                        <span>SAR {{ number_format($wheelchair->wheelchairType->deposit_amount, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Similar Wheelchairs -->
            @if($similarWheelchairs->count() > 0)
                <div class="detail-section">
                    <h3 class="section-title">Similar Wheelchairs</h3>
                    <div class="similar-scroll">
                        @foreach($similarWheelchairs as $similar)
                            <div class="similar-card" onclick="window.location.href='{{ route('wheelchairs.show', $similar) }}'">
                                <div class="similar-image">
                                    @if($similar->wheelchairType->image)
                                        <img src="{{ asset('storage/' . $similar->wheelchairType->image) }}"
                                            alt="{{ $similar->wheelchairType->name }}">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=200&h=150&fit=crop"
                                            alt="Wheelchair">
                                    @endif
                                </div>
                                <div class="similar-info">
                                    <div class="similar-name">{{ $similar->wheelchairType->name }}</div>
                                    <div class="similar-price">SAR {{ number_format($similar->wheelchairType->daily_rate, 0) }}/day
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Booking Footer -->
    <div class="booking-footer">
        <div class="footer-price">
            <span class="footer-price-amount">SAR {{ number_format($wheelchair->wheelchairType->daily_rate, 0) }}</span>
            <span class="footer-price-unit">/ day</span>
        </div>
        @auth
            <a href="{{ route('bookings.create', $wheelchair) }}" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-calendar-check"></i>
                Book Now
            </a>
        @else
            <a href="{{ route('web.login') }}?redirect={{ route('bookings.create', $wheelchair) }}"
                class="btn btn-primary btn-lg">
                <i class="fa-solid fa-calendar-check"></i>
                Login to Book
            </a>
        @endauth
    </div>
@endsection

@push('styles')
    <style>
        .detail-header {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            background: var(--bg-secondary);
            position: sticky;
            top: 0;
            z-index: 100;
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
            font-size: 1.125rem;
            font-weight: 600;
        }

        .favorite-btn-header {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-input);
            border-radius: var(--radius-md);
            color: var(--text-muted);
            cursor: pointer;
        }

        .favorite-btn-header.active {
            color: var(--error);
        }

        .detail-content {
            padding-bottom: 120px;
        }

        .image-gallery {
            width: 100%;
            height: 250px;
            background: var(--bg-input);
            position: relative;
        }

        .image-gallery img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-badge {
            position: absolute;
            bottom: var(--spacing-md);
            left: var(--spacing-md);
        }

        .wheelchair-detail-info {
            padding: var(--spacing-md);
        }

        .detail-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--spacing-lg);
        }

        .detail-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
        }

        .detail-location {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .detail-location i {
            color: var(--secondary);
        }

        .detail-code {
            color: var(--secondary);
            font-size: 0.875rem;
            font-family: monospace;
            margin-bottom: 2px;
        }

        .detail-code i {
            color: var(--primary-light);
            margin-right: 4px;
        }

        .detail-brand {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 2px;
        }

        .detail-brand i {
            color: var(--primary-light);
            margin-right: 4px;
        }

        .detail-price {
            text-align: right;
        }

        .detail-price .price-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
            display: block;
        }

        .detail-price .price-unit {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .spec-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--spacing-xs);
            padding: var(--spacing-md);
            background: var(--bg-card);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
        }

        .spec-card i {
            font-size: 1.5rem;
            color: var(--primary-light);
        }

        .spec-value {
            font-weight: 700;
            font-size: 1rem;
        }

        .spec-label {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .detail-section {
            margin-bottom: var(--spacing-lg);
        }

        .detail-section .section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: var(--spacing-md);
        }

        .detail-description {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        .features-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-sm);
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .feature-item i {
            color: var(--success);
        }

        .pricing-table {
            background: var(--bg-card);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .pricing-row {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-md);
            border-bottom: 1px solid var(--border);
        }

        .pricing-row:last-child {
            border-bottom: none;
        }

        .price-gold {
            color: var(--secondary);
            font-weight: 600;
        }

        .similar-scroll {
            display: flex;
            gap: var(--spacing-md);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .similar-scroll::-webkit-scrollbar {
            display: none;
        }

        .similar-card {
            min-width: 160px;
            background: var(--bg-card);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            overflow: hidden;
            cursor: pointer;
        }

        .similar-image {
            height: 100px;
            background: var(--bg-input);
        }

        .similar-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .similar-info {
            padding: var(--spacing-sm);
        }

        .similar-name {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 2px;
        }

        .similar-price {
            color: var(--secondary);
            font-size: 0.75rem;
        }

        .booking-footer {
            position: fixed;
            bottom: 70px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
            padding: var(--spacing-md);
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            z-index: 150;
        }

        .footer-price {
            flex: 1;
        }

        .footer-price-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .footer-price-unit {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
    </style>
@endpush