@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Bookings</div>
            <div class="stat-value">{{ number_format($stats['total_bookings']) }}</div>
            <div class="stat-change positive">
                <i class="fa-solid fa-arrow-up"></i>
                All time
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fa-solid fa-saudi-riyal-sign"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Revenue (MTD)</div>
            <div class="stat-value">SAR {{ number_format($stats['revenue_mtd']) }}</div>
            <div class="stat-change positive">
                <i class="fa-solid fa-arrow-up"></i>
                This month
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fa-solid fa-wheelchair"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Active Rentals</div>
            <div class="stat-value">{{ $stats['active_rentals'] }}</div>
            <div class="stat-change positive">
                <i class="fa-solid fa-arrow-up"></i>
                Currently rented
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Customers</div>
            <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
            <div class="stat-change positive">
                <i class="fa-solid fa-arrow-up"></i>
                Registered users
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings & Wheelchair Availability -->
<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Bookings</h3>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Wheelchair</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td>
                            <div class="flex items-center gap-sm">
                                <div class="avatar avatar-sm">{{ substr($booking->user->name ?? 'U', 0, 2) }}</div>
                                <div>
                                    <div>{{ $booking->user->name ?? 'Unknown' }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $booking->booking_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $booking->wheelchair->wheelchairType->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $booking->status === 'active' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'completed' ? 'info' : 'danger')) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>SAR {{ number_format($booking->total_amount) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No bookings yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Wheelchair Availability</h3>
            <a href="{{ route('admin.wheelchairs.index') }}" class="btn btn-secondary btn-sm">Manage</a>
        </div>
        <div class="card-body">
            @forelse($wheelchairAvailability as $type)
            <div class="mb-lg">
                <div class="flex justify-between items-center mb-sm">
                    <span>{{ $type->name }}</span>
                    @php
                        $percentage = $type->wheelchairs_count > 0 ? ($type->available_count / $type->wheelchairs_count) * 100 : 0;
                        $color = $percentage >= 70 ? 'success' : ($percentage >= 30 ? 'warning' : 'error');
                    @endphp
                    <span style="color: var(--{{ $color }});">{{ $type->available_count }}/{{ $type->wheelchairs_count }} Available</span>
                </div>
                <div style="height: 8px; background: var(--bg-input); border-radius: 4px; overflow: hidden;">
                    <div style="width: {{ $percentage }}%; height: 100%; background: var(--{{ $color }}); border-radius: 4px;"></div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted">No wheelchair types configured</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
