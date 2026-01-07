@extends('layouts.app')

@section('title', 'Booking ' . $booking->booking_code)

@section('content')
    <div class="booking-header">
        <a href="{{ route('bookings.index') }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="booking-header-title">Booking Details</h1>
        <span class="status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
    </div>

    <div class="screen-content" style="padding-bottom: 100px;">
        @if(session('success'))
            <div class="alert alert-success mb-md"
                style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--success); border-radius: var(--radius-md); padding: var(--spacing-md); color: var(--success);">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Booking Code Card -->
        <div class="booking-code-card">
            <div class="booking-code-header">
                <i class="fa-solid fa-qrcode"></i>
                <div>
                    <div class="booking-code-label">Booking Code</div>
                    <div class="booking-code-value">{{ $booking->booking_code }}</div>
                </div>
            </div>
        </div>

        <!-- Wheelchair Info -->
        <div class="section-card">
            <div class="section-card-header">
                <i class="fa-solid fa-wheelchair"></i>
                Wheelchair
            </div>
            <div class="section-card-body">
                <div class="wheelchair-summary">
                    <div class="summary-image">
                        @if($booking->wheelchair->wheelchairType->image)
                            <img src="{{ asset('storage/' . $booking->wheelchair->wheelchairType->image) }}" alt="Wheelchair">
                        @else
                            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=200&h=200&fit=crop"
                                alt="Wheelchair">
                        @endif
                    </div>
                    <div class="summary-info">
                        <h3 class="summary-name">{{ $booking->wheelchair->wheelchairType->name }}</h3>
                        <p class="summary-station">Code: {{ $booking->wheelchair->code }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rental Period -->
        <div class="section-card">
            <div class="section-card-header">
                <i class="fa-solid fa-calendar"></i>
                Rental Period
            </div>
            <div class="section-card-body">
                <div class="date-display">
                    <div class="date-item">
                        <div class="date-label">Start Date</div>
                        <div class="date-value">{{ $booking->start_date->format('D, M j, Y') }}</div>
                    </div>
                    <div class="date-separator">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                    <div class="date-item">
                        <div class="date-label">End Date</div>
                        <div class="date-value">{{ $booking->end_date->format('D, M j, Y') }}</div>
                    </div>
                </div>
                <div class="duration-badge">
                    <i class="fa-solid fa-clock"></i>
                    {{ $booking->days }} days rental
                </div>
            </div>
        </div>

        <!-- Pickup Info -->
        <div class="section-card">
            <div class="section-card-header">
                <i class="fa-solid fa-location-dot"></i>
                {{ $booking->pickup_type == 'delivery' ? 'Delivery' : 'Pickup' }} Location
            </div>
            <div class="section-card-body">
                @if($booking->pickup_type == 'delivery')
                    <p><strong>Delivery Address:</strong></p>
                    <p>{{ $booking->delivery_address }}</p>
                @else
                    <p><strong>{{ $booking->station->name }}</strong></p>
                    <p class="text-muted">{{ $booking->station->address }}</p>
                @endif
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="section-card">
            <div class="section-card-header">
                <i class="fa-solid fa-receipt"></i>
                Payment Summary
            </div>
            <div class="section-card-body">
                <div class="pricing-breakdown">
                    <div class="price-row">
                        <span class="price-label">Rental ({{ $booking->days }} days)</span>
                        <span class="price-value">SAR {{ number_format($booking->rental_amount, 0) }}</span>
                    </div>
                    @if($booking->discount_amount > 0)
                        <div class="price-row">
                            <span class="price-label">Discount
                                {{ $booking->promo_code ? '(' . $booking->promo_code . ')' : '' }}</span>
                            <span class="price-value discount">- SAR {{ number_format($booking->discount_amount, 0) }}</span>
                        </div>
                    @endif
                    <div class="price-row">
                        <span class="price-label">Delivery Fee</span>
                        <span class="price-value">SAR {{ number_format($booking->delivery_fee, 0) }}</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">VAT (15%)</span>
                        <span class="price-value">SAR {{ number_format($booking->vat_amount, 0) }}</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Refundable Deposit</span>
                        <span class="price-value">SAR {{ number_format($booking->deposit_amount, 0) }}</span>
                    </div>
                    <div class="price-row total">
                        <span class="price-label" style="font-weight: 600; color: var(--text-primary);">Total</span>
                        <span class="price-value total">SAR {{ number_format($booking->total_amount, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if($booking->canBeCancelled())
            <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                @csrf
                <button type="submit" class="btn btn-secondary btn-full" style="margin-bottom: var(--spacing-md);">
                    <i class="fa-solid fa-times"></i>
                    Cancel Booking
                </button>
            </form>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .booking-header {
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

        .booking-header-title {
            flex: 1;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .booking-code-card {
            background: var(--gradient-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }

        .booking-code-header {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .booking-code-header i {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .booking-code-label {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        .booking-code-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .section-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-lg);
            overflow: hidden;
        }

        .section-card-header {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-md);
            background: var(--bg-input);
            font-weight: 600;
        }

        .section-card-header i {
            color: var(--primary-light);
        }

        .section-card-body {
            padding: var(--spacing-md);
        }

        .wheelchair-summary {
            display: flex;
            gap: var(--spacing-md);
        }

        .summary-image {
            width: 80px;
            height: 80px;
            background: var(--bg-input);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .summary-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .summary-info {
            flex: 1;
        }

        .summary-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .summary-station {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .date-display {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-md);
        }

        .date-item {
            flex: 1;
            text-align: center;
            padding: var(--spacing-md);
            background: var(--bg-input);
            border-radius: var(--radius-md);
        }

        .date-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .date-value {
            font-weight: 600;
        }

        .date-separator {
            color: var(--primary-light);
        }

        .duration-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: var(--spacing-sm) var(--spacing-md);
            background: rgba(27, 77, 62, 0.2);
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            color: var(--primary-light);
        }

        .pricing-breakdown {
            margin-top: var(--spacing-md);
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-sm) 0;
        }

        .price-row.total {
            border-top: 1px solid var(--border);
            margin-top: var(--spacing-sm);
            padding-top: var(--spacing-md);
        }

        .price-label {
            color: var(--text-secondary);
        }

        .price-value {
            font-weight: 500;
        }

        .price-value.discount {
            color: var(--success);
        }

        .price-value.total {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning);
        }

        .status-confirmed {
            background: rgba(59, 130, 246, 0.2);
            color: var(--info);
        }

        .status-active {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .status-completed {
            background: rgba(100, 116, 139, 0.2);
            color: var(--text-muted);
        }

        .status-cancelled {
            background: rgba(239, 68, 68, 0.2);
            color: var(--error);
        }
    </style>
@endpush