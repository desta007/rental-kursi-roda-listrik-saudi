@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Header -->
    <div class="home-header">
        <div class="user-greeting">
            @auth
                <div class="avatar">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <div>
                    <p class="greeting-text">Welcome back,</p>
                    <h3 class="user-name">{{ auth()->user()->name }}</h3>
                </div>
            @else
                <div class="avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <p class="greeting-text">Welcome to</p>
                    <h3 class="user-name">MobilityKSA</h3>
                </div>
            @endauth
        </div>
        <div class="notification-btn">
            <i class="fa-solid fa-bell"></i>
            @auth
                @if(auth()->user()->bookings()->where('status', 'confirmed')->count() > 0)
                    <span class="notification-badge">{{ auth()->user()->bookings()->where('status', 'confirmed')->count() }}</span>
                @endif
            @endauth
        </div>
    </div>

    <div class="screen-content" style="padding-top: 0;">
        <!-- Location Selector -->
        <div style="padding: 0 var(--spacing-md); margin-bottom: var(--spacing-md);">
            <div class="location-selector" id="locationSelector">
                <i class="fa-solid fa-location-dot location-icon"></i>
                <span class="location-text">
                    @if($selectedLocation)
                        {{ $selectedLocation->name }}, {{ $selectedLocation->city }}
                    @else
                        Select Location
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down" style="color: var(--text-muted);"></i>
            </div>
        </div>

        <!-- Location Modal -->
        <div class="modal-backdrop" id="locationModalBackdrop"></div>
        <div class="location-modal" id="locationModal">
            <div class="modal-header">
                <h3 class="modal-title">Select Location</h3>
                <button type="button" class="modal-close" id="closeLocationModal">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                @php
                    $groupedStations = $allStations->groupBy('city');
                @endphp
                @foreach($groupedStations as $city => $cityStations)
                    <div class="location-group">
                        <h4 class="location-group-title">{{ $city }}</h4>
                        @foreach($cityStations as $station)
                            <div class="location-option {{ $selectedLocation && $selectedLocation->id == $station->id ? 'selected' : '' }}"
                                data-location-id="{{ $station->id }}"
                                data-location-name="{{ $station->name }}, {{ $station->city }}">
                                <div class="location-option-icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="location-option-info">
                                    <div class="location-option-name">{{ $station->name }}</div>
                                    <div class="location-option-address">{{ $station->address }}</div>
                                </div>
                                @if($selectedLocation && $selectedLocation->id == $station->id)
                                    <i class="fa-solid fa-check-circle location-option-check"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Promo Banner -->
        <div class="promo-banner">
            <span class="promo-tag">🎉 Special Offer</span>
            <h3 class="promo-title">Hajj Season Discount</h3>
            <p class="promo-text">Get 20% off on weekly rentals with code HAJJ2026</p>
            <a href="{{ route('wheelchairs.index') }}" class="promo-btn">
                Book Now <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Active Booking (if any) -->
        @if($activeBooking)
            <div class="active-booking">
                <div class="active-booking-header">
                    <span class="active-badge">Active Rental</span>
                    <span class="booking-time-left">{{ $activeBooking->end_date->diffForHumans() }}</span>
                </div>
                <div class="active-booking-content">
                    <div class="active-wheelchair-image">
                        @if($activeBooking->wheelchair->wheelchairType->image)
                            <img src="{{ asset('storage/' . $activeBooking->wheelchair->wheelchairType->image) }}" alt="Wheelchair">
                        @else
                            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=200&h=200&fit=crop"
                                alt="Wheelchair">
                        @endif
                    </div>
                    <div class="active-booking-info">
                        <h4 class="active-wheelchair-name">{{ $activeBooking->wheelchair->wheelchairType->name }}</h4>
                        <p class="active-booking-dates">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $activeBooking->start_date->format('M j') }} - {{ $activeBooking->end_date->format('M j, Y') }}
                        </p>
                        <div class="active-booking-stats">
                            <div class="stat-item">
                                <i class="fa-solid fa-battery-three-quarters"></i>
                                <span>{{ $activeBooking->wheelchair->wheelchairType->battery_range_km }}km range</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="section-header" style="padding: 0 var(--spacing-md);">
            <h3 class="section-title">Quick Actions</h3>
        </div>
        <div class="quick-actions">
            <a href="{{ route('wheelchairs.index') }}" class="quick-action">
                <div class="quick-action-icon">
                    <i class="fa-solid fa-wheelchair"></i>
                </div>
                <span class="quick-action-label">Browse</span>
            </a>
            <a href="{{ route('bookings.index') }}" class="quick-action">
                <div class="quick-action-icon" style="background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="quick-action-label">My Bookings</span>
            </a>
            <a href="#" class="quick-action">
                <div class="quick-action-icon" style="background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <span class="quick-action-label">Support</span>
            </a>
            <a href="#" class="quick-action">
                <div class="quick-action-icon" style="background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%);">
                    <i class="fa-solid fa-question-circle"></i>
                </div>
                <span class="quick-action-label">Help</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div style="padding: 0 var(--spacing-md); margin-bottom: var(--spacing-lg);">
            <form action="{{ route('wheelchairs.index') }}" method="GET">
                <div class="search-bar">
                    <i class="fa-solid fa-search search-icon"></i>
                    <input type="text" name="search" placeholder="Search wheelchairs...">
                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                        <i class="fa-solid fa-sliders" style="color: var(--primary-light);"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Nearby Stations -->
        <div class="section-header" style="padding: 0 var(--spacing-md);">
            <h3 class="section-title">Nearby Pickup Stations</h3>
            <a href="#" class="section-link">View All</a>
        </div>

        <div class="stations-scroll mb-lg">
            @foreach($stations as $station)
                <div class="station-card station-card-scroll">
                    <div class="station-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="station-info">
                        <h4 class="station-name">{{ $station->name }}</h4>
                        <p class="station-address">{{ $station->address }}</p>
                    </div>
                    <div class="station-available">
                        <div class="available-count">{{ $station->wheelchairs_count }}</div>
                        <div class="available-label">Available</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Featured Wheelchairs -->
        <div class="section-header" style="padding: 0 var(--spacing-md);">
            <h3 class="section-title">Featured Wheelchairs</h3>
            <a href="{{ route('wheelchairs.index') }}" class="section-link">See All</a>
        </div>

        <div style="padding: 0 var(--spacing-md);">
            @foreach($featuredWheelchairs as $wheelchair)
                <div class="wheelchair-card" onclick="window.location.href='{{ route('wheelchairs.show', $wheelchair) }}'">
                    <div class="wheelchair-image">
                        @if($wheelchair->wheelchairType->image)
                            <img src="{{ asset('storage/' . $wheelchair->wheelchairType->image) }}"
                                alt="{{ $wheelchair->wheelchairType->name }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=200&h=200&fit=crop"
                                alt="Wheelchair">
                        @endif
                    </div>
                    <div class="wheelchair-info">
                        <h4 class="wheelchair-name">{{ $wheelchair->wheelchairType->name }}</h4>
                        <div class="wheelchair-specs">
                            <span class="spec-badge"><i class="fa-solid fa-battery-full"></i>
                                {{ $wheelchair->wheelchairType->battery_range_km }}km</span>
                            <span class="spec-badge"><i class="fa-solid fa-weight-hanging"></i>
                                {{ $wheelchair->wheelchairType->max_weight_kg }}kg</span>
                        </div>
                        <div class="wheelchair-price">
                            <span class="price-amount">SAR
                                {{ number_format($wheelchair->wheelchairType->daily_rate, 0) }}</span>
                            <span class="price-unit">/ day</span>
                        </div>
                    </div>
                    <span class="status-badge status-available">Available</span>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .home-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-md);
        }

        .user-greeting {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .greeting-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .user-name {
            font-weight: 600;
            font-size: 1.125rem;
        }

        .notification-btn {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-input);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            position: relative;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            background: var(--error);
            border-radius: 50%;
            font-size: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .promo-banner {
            background: linear-gradient(135deg, #1B4D3E 0%, #D4AF37 100%);
            border-radius: var(--radius-xl);
            padding: var(--spacing-lg);
            margin: var(--spacing-md);
            position: relative;
            overflow: hidden;
        }

        .promo-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .promo-tag {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: var(--spacing-sm);
        }

        .promo-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
        }

        .promo-text {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: var(--spacing-md);
        }

        .promo-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-md);
            background: white;
            color: var(--primary);
            border-radius: var(--radius-full);
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-md);
            padding: 0 var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-md);
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all var(--transition-fast);
            text-decoration: none;
        }

        .quick-action:hover {
            border-color: var(--primary-light);
            transform: translateY(-2px);
        }

        .quick-action-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-primary);
            border-radius: var(--radius-md);
            color: white;
            font-size: 1.25rem;
        }

        .quick-action-label {
            font-size: 0.75rem;
            color: var(--text-primary);
            text-align: center;
        }

        .stations-scroll {
            display: flex;
            gap: var(--spacing-md);
            overflow-x: auto;
            padding: 0 var(--spacing-md);
            -webkit-overflow-scrolling: touch;
        }

        .stations-scroll::-webkit-scrollbar {
            display: none;
        }

        .station-card {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            cursor: pointer;
        }

        .station-card-scroll {
            min-width: 280px;
        }

        .station-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-input);
            border-radius: var(--radius-md);
            color: var(--primary-light);
            font-size: 1.25rem;
        }

        .station-info {
            flex: 1;
        }

        .station-name {
            font-weight: 600;
            margin-bottom: 2px;
        }

        .station-address {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .station-available {
            text-align: right;
        }

        .available-count {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-light);
        }

        .available-label {
            font-size: 0.625rem;
            color: var(--text-muted);
        }

        .active-booking {
            margin: 0 var(--spacing-md);
            margin-bottom: var(--spacing-lg);
            background: linear-gradient(135deg, rgba(27, 77, 62, 0.3) 0%, rgba(212, 175, 55, 0.1) 100%);
            border: 1px solid var(--primary);
            border-radius: var(--radius-xl);
            padding: var(--spacing-md);
        }

        .active-booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-md);
        }

        .active-badge {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: 4px 12px;
            background: var(--success);
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .booking-time-left {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .active-booking-content {
            display: flex;
            gap: var(--spacing-md);
        }

        .active-wheelchair-image {
            width: 80px;
            height: 80px;
            background: var(--bg-input);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .active-wheelchair-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .active-booking-info {
            flex: 1;
        }

        .active-wheelchair-name {
            font-weight: 600;
            margin-bottom: var(--spacing-xs);
        }

        .active-booking-dates {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: var(--spacing-sm);
        }

        .active-booking-stats {
            display: flex;
            gap: var(--spacing-lg);
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .stat-item i {
            color: var(--primary-light);
        }

        /* Location Modal Styles */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 200;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .location-modal {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%) translateY(100%);
            width: 100%;
            max-width: 430px;
            max-height: 70vh;
            background: var(--bg-secondary);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            z-index: 201;
            transition: transform 0.3s ease;
        }

        .location-modal.active {
            transform: translateX(-50%) translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-md);
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .modal-close {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-input);
            border: none;
            border-radius: var(--radius-md);
            color: var(--text-primary);
            cursor: pointer;
        }

        .modal-body {
            padding: var(--spacing-md);
            max-height: calc(70vh - 60px);
            overflow-y: auto;
        }

        .location-group {
            margin-bottom: var(--spacing-md);
        }

        .location-group-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: var(--spacing-sm);
            padding-left: var(--spacing-sm);
        }

        .location-option {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-sm);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .location-option:hover {
            border-color: var(--primary-light);
        }

        .location-option.selected {
            border-color: var(--primary);
            background: rgba(27, 77, 62, 0.1);
        }

        .location-option-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-input);
            border-radius: var(--radius-md);
            color: var(--primary-light);
        }

        .location-option-info {
            flex: 1;
        }

        .location-option-name {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .location-option-address {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .location-option-check {
            color: var(--primary);
            font-size: 1.25rem;
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const locationSelector = document.getElementById('locationSelector');
        const locationModal = document.getElementById('locationModal');
        const locationModalBackdrop = document.getElementById('locationModalBackdrop');
        const closeLocationModal = document.getElementById('closeLocationModal');
        const locationOptions = document.querySelectorAll('.location-option');

        // Open modal
        locationSelector.addEventListener('click', function() {
            locationModal.classList.add('active');
            locationModalBackdrop.classList.add('active');
        });

        // Close modal
        function closeModal() {
            locationModal.classList.remove('active');
            locationModalBackdrop.classList.remove('active');
        }

        closeLocationModal.addEventListener('click', closeModal);
        locationModalBackdrop.addEventListener('click', closeModal);

        // Select location
        locationOptions.forEach(option => {
            option.addEventListener('click', function() {
                const locationId = this.dataset.locationId;
                const locationName = this.dataset.locationName;

                // Update UI immediately
                locationSelector.querySelector('.location-text').textContent = locationName;

                // Remove selected class from all options
                locationOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');

                // Send AJAX request to save location
                fetch('{{ route("home.setLocation") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ location_id: locationId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to refresh data
                        window.location.reload();
                    }
                });

                closeModal();
            });
        });
    });
</script>
@endpush