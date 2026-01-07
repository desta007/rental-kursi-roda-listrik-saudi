@extends('layouts.app')

@section('title', 'Payment')

@section('content')
    <div class="booking-header">
        <a href="{{ route('bookings.index') }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="booking-header-title">Payment</h1>
        <span class="step-indicator">Step 2/2</span>
    </div>

    <div class="screen-content" style="padding-bottom: 120px;">
        <!-- Order Summary -->
        <div class="section-card">
            <div class="section-card-header">
                <i class="fa-solid fa-receipt"></i>
                Order Summary
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
                        <p class="summary-station">{{ $booking->booking_code }}</p>
                        <p class="summary-dates">
                            {{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}
                        </p>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="pricing-breakdown">
                    <div class="price-row">
                        <span class="price-label">Rental ({{ $booking->days }} days)</span>
                        <span class="price-value">SAR {{ number_format($booking->rental_amount, 0) }}</span>
                    </div>
                    @if($booking->discount_amount > 0)
                        <div class="price-row">
                            <span class="price-label">Discount</span>
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

        <!-- Payment Method -->
        <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="section-card">
                <div class="section-card-header">
                    <i class="fa-solid fa-credit-card"></i>
                    Payment Method
                </div>
                <div class="section-card-body">
                    <div class="payment-methods">
                        <label class="payment-method selected">
                            <input type="radio" name="payment_method" value="card" style="display: none;" checked>
                            <div class="payment-method-icon">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <div class="payment-method-info">
                                <div class="payment-method-name">Credit / Debit Card</div>
                                <div class="payment-method-desc">Visa, Mastercard, AMEX</div>
                            </div>
                            <div class="payment-method-check">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                        </label>

                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="mada" style="display: none;">
                            <div class="payment-method-icon" style="background: #00a859;">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div class="payment-method-info">
                                <div class="payment-method-name">Mada</div>
                                <div class="payment-method-desc">Saudi Debit Cards</div>
                            </div>
                            <div class="payment-method-check">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                        </label>

                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="apple_pay" style="display: none;">
                            <div class="payment-method-icon" style="background: #000;">
                                <i class="fa-brands fa-apple-pay"></i>
                            </div>
                            <div class="payment-method-info">
                                <div class="payment-method-name">Apple Pay</div>
                                <div class="payment-method-desc">Quick & Secure</div>
                            </div>
                            <div class="payment-method-check">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Security Info -->
            <div class="security-info">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Your payment is secured with 256-bit SSL encryption</span>
            </div>
        </form>
    </div>

    <!-- Payment Footer -->
    <div class="booking-footer">
        <div class="footer-total">
            <div class="footer-total-label">Total Amount</div>
            <div class="footer-total-value">SAR {{ number_format($booking->total_amount, 0) }}</div>
        </div>
        <button type="submit" form="paymentForm" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-lock"></i>
            Pay Now
        </button>
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

        .step-indicator {
            font-size: 0.875rem;
            color: var(--text-secondary);
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
            width: 70px;
            height: 70px;
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

        .summary-station,
        .summary-dates {
            font-size: 0.75rem;
            color: var(--text-muted);
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

        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .payment-method {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            background: var(--bg-input);
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .payment-method.selected {
            border-color: var(--primary);
            background: rgba(27, 77, 62, 0.2);
        }

        .payment-method-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            border-radius: var(--radius-md);
            color: white;
            font-size: 1.5rem;
        }

        .payment-method-info {
            flex: 1;
        }

        .payment-method-name {
            font-weight: 600;
            margin-bottom: 2px;
        }

        .payment-method-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .payment-method-check {
            color: var(--border);
            font-size: 1.25rem;
        }

        .payment-method.selected .payment-method-check {
            color: var(--primary);
        }

        .security-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-md);
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .security-info i {
            color: var(--success);
        }

        .booking-footer {
            position: fixed;
            bottom: 0;
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
            z-index: 100;
        }

        .footer-total {
            flex: 1;
        }

        .footer-total-label {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .footer-total-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--secondary);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function () {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    </script>
@endpush