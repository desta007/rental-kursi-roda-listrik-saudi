@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
    <!-- Header -->
    <div class="booking-header">
        <a href="{{ route('wheelchairs.show', $wheelchair) }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="booking-header-title">Create Booking</h1>
        <span class="step-indicator">Step 1/2</span>
    </div>

    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
        @csrf
        <input type="hidden" name="wheelchair_id" value="{{ $wheelchair->id }}">

        <div class="booking-content">
            <!-- Wheelchair Summary -->
            <div class="wheelchair-summary">
                <div class="summary-image">
                    @if($wheelchair->wheelchairType->image)
                        <img src="{{ asset('storage/' . $wheelchair->wheelchairType->image) }}" alt="{{ $wheelchair->wheelchairType->name }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=200&h=200&fit=crop" alt="Wheelchair">
                    @endif
                </div>
                <div class="summary-info">
                    <h3 class="summary-name">{{ $wheelchair->wheelchairType->name }}</h3>
                    <p class="summary-station">
                        <i class="fa-solid fa-location-dot"></i> {{ $wheelchair->station->name }}
                    </p>
                    <div class="summary-price">SAR {{ number_format($wheelchair->wheelchairType->daily_rate, 0) }} / day</div>
                </div>
            </div>

            @if($errors->any())
            <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); border-radius: var(--radius-md); padding: var(--spacing-md); margin-bottom: var(--spacing-md); color: var(--error);">
                <ul style="margin: 0; padding-left: var(--spacing-md);">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Rental Period -->
            <div class="section-card">
                <div class="section-card-header">
                    <i class="fa-solid fa-calendar"></i>
                    Rental Period
                </div>
                <div class="section-card-body">
                    <div class="date-picker">
                        <div class="date-input">
                            <div class="date-input-label">Start Date</div>
                            <input type="date" name="start_date" class="form-input" 
                                   value="{{ old('start_date', now()->format('Y-m-d')) }}" 
                                   min="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="date-input">
                            <div class="date-input-label">End Date</div>
                            <input type="date" name="end_date" class="form-input" 
                                   value="{{ old('end_date', now()->addDays(7)->format('Y-m-d')) }}" 
                                   min="{{ now()->addDay()->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="duration-pills">
                        <div class="duration-pill" data-days="3">3 Days</div>
                        <div class="duration-pill active" data-days="7">7 Days</div>
                        <div class="duration-pill" data-days="14">14 Days</div>
                        <div class="duration-pill" data-days="30">30 Days</div>
                    </div>
                </div>
            </div>

            <!-- Pickup Method -->
            <div class="section-card">
                <div class="section-card-header">
                    <i class="fa-solid fa-truck"></i>
                    Pickup Method
                </div>
                <div class="section-card-body">
                    <div class="pickup-options">
                        <label class="pickup-option {{ old('pickup_type', 'self') == 'self' ? 'selected' : '' }}">
                            <input type="radio" name="pickup_type" value="self" style="display: none;" {{ old('pickup_type', 'self') == 'self' ? 'checked' : '' }}>
                            <div class="pickup-option-icon">
                                <i class="fa-solid fa-person-walking-luggage"></i>
                            </div>
                            <div class="pickup-option-title">Self Pickup</div>
                            <div class="pickup-option-subtitle">Free</div>
                        </label>
                        <label class="pickup-option {{ old('pickup_type') == 'delivery' ? 'selected' : '' }}">
                            <input type="radio" name="pickup_type" value="delivery" style="display: none;" {{ old('pickup_type') == 'delivery' ? 'checked' : '' }}>
                            <div class="pickup-option-icon">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                            <div class="pickup-option-title">Delivery</div>
                            <div class="pickup-option-subtitle">SAR 30</div>
                        </label>
                    </div>

                    <!-- Station Selection (for self pickup) -->
                    <div class="station-selection" id="station-selection">
                        <div class="form-group mt-md">
                            <label class="form-label">Pickup Station</label>
                            <select name="station_id" class="form-input">
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}" {{ $station->id == $wheelchair->station_id ? 'selected' : '' }}>
                                        {{ $station->name }} - {{ $station->address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Delivery Address (for delivery) -->
                    <div class="address-form hidden" id="address-form">
                        <div class="form-group mt-md">
                            <label class="form-label">Delivery Address</label>
                            <input type="text" name="delivery_address" class="form-input" 
                                   placeholder="Hotel name or address" value="{{ old('delivery_address') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promo Code -->
            <div class="section-card">
                <div class="section-card-header">
                    <i class="fa-solid fa-ticket"></i>
                    Promo Code
                </div>
                <div class="section-card-body">
                    <div class="promo-input">
                        <input type="text" name="promo_code" class="form-input" placeholder="Enter promo code" value="{{ old('promo_code') }}">
                    </div>
                    <p class="text-muted" style="font-size: 0.75rem; margin-top: var(--spacing-sm);">
                        Try code: HAJJ2026 for 20% off
                    </p>
                </div>
            </div>

            <!-- Notes -->
            <div class="section-card">
                <div class="section-card-header">
                    <i class="fa-solid fa-sticky-note"></i>
                    Special Requests (Optional)
                </div>
                <div class="section-card-body">
                    <textarea name="notes" class="form-input" rows="3" placeholder="Any special requests or notes...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Terms -->
            <div class="terms-checkbox">
                <input type="checkbox" id="terms" required>
                <label class="terms-text" for="terms">
                    I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Cancellation Policy</a>. I understand that the deposit will be refunded after safe return.
                </label>
            </div>
        </div>

        <!-- Footer -->
        <div class="booking-footer">
            <div class="footer-total">
                <div class="footer-total-label">Estimated Total</div>
                <div class="footer-total-value" id="estimatedTotal">SAR {{ number_format($wheelchair->wheelchairType->weekly_rate + $wheelchair->wheelchairType->deposit_amount, 0) }}</div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-arrow-right"></i>
                Continue
            </button>
        </div>
    </form>
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

    .booking-content {
        padding: var(--spacing-md);
        padding-bottom: 120px;
    }

    .wheelchair-summary {
        display: flex;
        gap: var(--spacing-md);
        padding: var(--spacing-md);
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        margin-bottom: var(--spacing-lg);
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
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: var(--spacing-sm);
    }

    .summary-price {
        color: var(--secondary);
        font-weight: 600;
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

    .date-picker {
        display: flex;
        gap: var(--spacing-md);
    }

    .date-input {
        flex: 1;
    }

    .date-input-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: var(--spacing-xs);
    }

    .duration-pills {
        display: flex;
        gap: var(--spacing-sm);
        margin-top: var(--spacing-md);
        overflow-x: auto;
    }

    .duration-pill {
        padding: var(--spacing-sm) var(--spacing-md);
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: var(--radius-full);
        font-size: 0.875rem;
        white-space: nowrap;
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .duration-pill.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .pickup-options {
        display: flex;
        gap: var(--spacing-md);
    }

    .pickup-option {
        flex: 1;
        padding: var(--spacing-lg);
        background: var(--bg-input);
        border: 2px solid var(--border);
        border-radius: var(--radius-md);
        text-align: center;
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .pickup-option.selected {
        border-color: var(--primary);
        background: rgba(27, 77, 62, 0.2);
    }

    .pickup-option-icon {
        font-size: 2rem;
        color: var(--primary-light);
        margin-bottom: var(--spacing-sm);
    }

    .pickup-option-title {
        font-weight: 600;
        margin-bottom: 4px;
    }

    .pickup-option-subtitle {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .promo-input {
        display: flex;
        gap: var(--spacing-sm);
    }

    .promo-input input {
        flex: 1;
    }

    .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: var(--spacing-sm);
        padding: var(--spacing-md);
        background: var(--bg-input);
        border-radius: var(--radius-md);
    }

    .terms-checkbox input[type="checkbox"] {
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

    .hidden {
        display: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Pricing data from PHP
    const pricingData = {
        dailyRate: {{ $wheelchair->wheelchairType->daily_rate }},
        weeklyRate: {{ $wheelchair->wheelchairType->weekly_rate }},
        monthlyRate: {{ $wheelchair->wheelchairType->monthly_rate }},
        depositAmount: {{ $wheelchair->wheelchairType->deposit_amount }},
        deliveryFee: 30,
        vatRate: 0.15
    };

    // Calculate rental rate based on days (same logic as PHP calculateRate)
    function calculateRentalRate(days) {
        if (days >= 30) {
            return pricingData.monthlyRate * Math.ceil(days / 30);
        } else if (days >= 7) {
            return pricingData.weeklyRate * Math.ceil(days / 7);
        } else {
            return pricingData.dailyRate * days;
        }
    }

    // Calculate and update estimated total
    function updateEstimatedTotal() {
        const startDate = new Date(document.querySelector('input[name="start_date"]').value);
        const endDate = new Date(document.querySelector('input[name="end_date"]').value);
        
        // Calculate days
        const timeDiff = endDate - startDate;
        const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
        
        if (days <= 0 || isNaN(days)) {
            document.getElementById('estimatedTotal').textContent = 'SAR 0';
            return;
        }

        // Calculate rental amount
        const rentalAmount = calculateRentalRate(days);
        
        // Check pickup type
        const pickupType = document.querySelector('input[name="pickup_type"]:checked')?.value || 'self';
        const deliveryFee = pickupType === 'delivery' ? pricingData.deliveryFee : 0;
        
        // Calculate subtotal and VAT
        const subtotal = rentalAmount + deliveryFee;
        const vatAmount = subtotal * pricingData.vatRate;
        
        // Total with deposit
        const total = subtotal + vatAmount + pricingData.depositAmount;
        
        // Update display
        document.getElementById('estimatedTotal').textContent = 'SAR ' + Math.round(total).toLocaleString();
    }

    // Pickup option toggle
    document.querySelectorAll('.pickup-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.pickup-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');

            const pickupType = this.querySelector('input').value;
            const addressForm = document.getElementById('address-form');
            const stationSelection = document.getElementById('station-selection');
            
            if (pickupType === 'delivery') {
                addressForm.classList.remove('hidden');
                stationSelection.classList.add('hidden');
            } else {
                addressForm.classList.add('hidden');
                stationSelection.classList.remove('hidden');
            }

            // Update total when pickup method changes
            updateEstimatedTotal();
        });
    });

    // Duration pills
    document.querySelectorAll('.duration-pill').forEach(pill => {
        pill.addEventListener('click', function() {
            document.querySelectorAll('.duration-pill').forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            const days = parseInt(this.dataset.days);
            const startDate = new Date();
            const endDate = new Date();
            endDate.setDate(startDate.getDate() + days);

            document.querySelector('input[name="start_date"]').value = startDate.toISOString().split('T')[0];
            document.querySelector('input[name="end_date"]').value = endDate.toISOString().split('T')[0];

            // Update total when duration changes
            updateEstimatedTotal();
        });
    });

    // Date input change listeners
    document.querySelector('input[name="start_date"]').addEventListener('change', updateEstimatedTotal);
    document.querySelector('input[name="end_date"]').addEventListener('change', updateEstimatedTotal);

    // Initial calculation on page load
    updateEstimatedTotal();
</script>
@endpush
