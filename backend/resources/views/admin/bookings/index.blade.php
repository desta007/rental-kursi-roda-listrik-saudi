@extends('admin.layouts.app')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Bookings</h3>
        <div class="flex gap-sm">
            <form class="flex gap-sm" method="GET">
                <select name="status" class="form-input form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <input type="text" name="search" class="form-input" style="width: 200px;" 
                       placeholder="Search..." value="{{ request('search') }}">
            </form>
        </div>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Wheelchair</th>
                    <th>Dates</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
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
                        <div style="font-size: 0.875rem;">
                            {{ $booking->start_date?->format('d M Y') }} - {{ $booking->end_date?->format('d M Y') }}
                        </div>
                    </td>
                    <td>SAR {{ number_format($booking->total_amount) }}</td>
                    <td>
                        <span class="badge badge-{{ $booking->status === 'active' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'completed' ? 'info' : 'danger')) }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-sm">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No bookings found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="pagination">
        <div class="pagination-info">
            Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} results
        </div>
    </div>
    @endif
</div>
@endsection
