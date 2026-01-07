@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
    <div class="screen-header">
        <h1 style="font-size: 1.5rem; font-weight: 700;">My Bookings</h1>
    </div>

    <div class="screen-content">
        <!-- Status Tabs -->
        <div class="tabs-wrapper">
            <div class="tabs tabs-scrollable">
                <a href="{{ route('bookings.index') }}" class="tab {{ $status == 'all' ? 'active' : '' }}">All</a>
                <a href="{{ route('bookings.index', ['status' => 'pending']) }}"
                    class="tab {{ $status == 'pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}"
                    class="tab {{ $status == 'confirmed' ? 'active' : '' }}">Confirmed</a>
                <a href="{{ route('bookings.index', ['status' => 'active']) }}"
                    class="tab {{ $status == 'active' ? 'active' : '' }}">Active</a>
                <a href="{{ route('bookings.index', ['status' => 'completed']) }}"
                    class="tab {{ $status == 'completed' ? 'active' : '' }}">Completed</a>
                <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}"
                    class="tab {{ $status == 'cancelled' ? 'active' : '' }}">Cancelled</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-md"
                style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--success); border-radius: var(--radius-md); padding: var(--spacing-md); color: var(--success);">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Bookings List -->
        @forelse($bookings as $booking)
            <div class="booking-card" onclick="window.location.href='{{ route('bookings.show', $booking) }}'">
                <div class="booking-card-header">
                    <span class="booking-code">{{ $booking->booking_code }}</span>
                    <span class="status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                </div>
                <div class="booking-card-content">
                    <div class="booking-wheelchair-image">
                        @if($booking->wheelchair->wheelchairType->image)
                            <img src="{{ asset('storage/' . $booking->wheelchair->wheelchairType->image) }}" alt="Wheelchair">
                        @else
                            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=200&h=200&fit=crop"
                                alt="Wheelchair">
                        @endif
                    </div>
                    <div class="booking-card-info">
                        <h4 class="booking-wheelchair-name">{{ $booking->wheelchair->wheelchairType->name }}</h4>
                        <p class="booking-wheelchair-details">
                            <span class="wheelchair-code">{{ $booking->wheelchair->code }}</span>
                            @if($booking->wheelchair->brand || $booking->wheelchair->model)
                                <span class="wheelchair-spec">{{ $booking->wheelchair->brand }}
                                    {{ $booking->wheelchair->model }}</span>
                            @endif
                        </p>
                        <p class="booking-dates">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}
                        </p>
                        <p class="booking-station">
                            <i class="fa-solid fa-location-dot"></i>
                            {{ $booking->station->name }}
                        </p>
                    </div>
                    <div class="booking-card-price">
                        <span class="booking-total">SAR {{ number_format($booking->total_amount, 0) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 class="empty-state-title">No Bookings Yet</h3>
                <p class="empty-state-text">Start by browsing our electric wheelchairs</p>
                <a href="{{ route('wheelchairs.index') }}" class="btn btn-primary">Browse Wheelchairs</a>
            </div>
        @endforelse

        @if($bookings->hasPages())
            <div style="margin-top: var(--spacing-md);">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .booking-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-md);
            overflow: hidden;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .booking-card:hover {
            border-color: var(--primary-light);
            transform: translateY(-2px);
        }

        .booking-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-sm) var(--spacing-md);
            background: var(--bg-input);
            border-bottom: 1px solid var(--border);
        }

        .booking-code {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .booking-card-content {
            display: flex;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
        }

        .booking-wheelchair-image {
            width: 70px;
            height: 70px;
            background: var(--bg-input);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .booking-wheelchair-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .booking-card-info {
            flex: 1;
        }

        .booking-wheelchair-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 2px;
        }

        .booking-wheelchair-details {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-xs);
            margin-bottom: var(--spacing-xs);
        }

        .wheelchair-code {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary);
            background: rgba(139, 92, 246, 0.15);
            padding: 2px 6px;
            border-radius: var(--radius-sm);
        }

        .wheelchair-spec {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .tabs-wrapper {
            margin: 0 calc(var(--spacing-md) * -1);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .tabs-wrapper::-webkit-scrollbar {
            display: none;
        }

        .tabs-scrollable {
            display: flex;
            flex-wrap: nowrap;
            padding: 0 var(--spacing-md);
            min-width: max-content;
        }

        .tabs-scrollable .tab {
            white-space: nowrap;
            flex-shrink: 0;
        }

        .booking-dates,
        .booking-station {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .booking-dates i,
        .booking-station i {
            width: 14px;
            color: var(--primary-light);
        }

        .booking-card-price {
            text-align: right;
        }

        .booking-total {
            font-size: 1.125rem;
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