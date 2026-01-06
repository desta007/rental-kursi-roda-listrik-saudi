@extends('admin.layouts.app')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Customer: {{ $user->name }}</h3>
        <div class="flex gap-sm">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fa-solid fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Status Badges -->
        <div class="mb-lg flex gap-sm">
            <span class="badge badge-{{ $user->status === 'active' ? 'success' : ($user->status === 'pending' ? 'warning' : 'danger') }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                {{ ucfirst($user->status) }}
            </span>
            <span class="badge badge-{{ $user->verification_status === 'verified' ? 'success' : ($user->verification_status === 'pending' ? 'warning' : 'danger') }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                {{ ucfirst($user->verification_status) }}
            </span>
        </div>

        <div class="grid-2 mb-lg" style="gap: 2rem;">
            <!-- Personal Info -->
            <div class="info-section">
                <h4 style="margin-bottom: 1rem; color: var(--text-primary);"><i class="fa-solid fa-user"></i> Personal Information</h4>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Name</td>
                        <td class="info-value">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Phone</td>
                        <td class="info-value">{{ $user->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Email</td>
                        <td class="info-value">{{ $user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Language</td>
                        <td class="info-value">{{ strtoupper($user->language ?? 'en') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Registered</td>
                        <td class="info-value">{{ $user->created_at?->format('d M Y, H:i') ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Identity Info -->
            <div class="info-section">
                <h4 style="margin-bottom: 1rem; color: var(--text-primary);"><i class="fa-solid fa-id-card"></i> Identity Information</h4>
                <table class="info-table">
                    <tr>
                        <td class="info-label">ID Type</td>
                        <td class="info-value">{{ ucfirst($user->identity_type ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">ID Number</td>
                        <td class="info-value">{{ $user->identity_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">ID Photo</td>
                        <td class="info-value">
                            @if($user->identity_photo)
                                <a href="{{ asset('storage/' . $user->identity_photo) }}" target="_blank" class="btn btn-secondary btn-sm">
                                    <i class="fa-solid fa-image"></i> View Photo
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Booking History -->
        <div class="info-section">
            <h4 style="margin-bottom: 1rem; color: var(--text-primary);"><i class="fa-solid fa-history"></i> Booking History ({{ $user->bookings->count() }})</h4>
            @if($user->bookings->count() > 0)
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking Code</th>
                            <th>Wheelchair</th>
                            <th>Dates</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->bookings->sortByDesc('created_at')->take(10) as $booking)
                        <tr>
                            <td>{{ $booking->booking_code }}</td>
                            <td>
                                <div>{{ $booking->wheelchair->wheelchairType->name ?? 'N/A' }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $booking->wheelchair->code ?? '' }}</div>
                            </td>
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
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($user->bookings->count() > 10)
            <div class="text-muted mt-sm" style="font-size: 0.875rem;">
                Showing 10 of {{ $user->bookings->count() }} bookings
            </div>
            @endif
            @else
            <p class="text-muted">No bookings yet</p>
            @endif
        </div>
    </div>
</div>

<style>
.info-section {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    padding: 1.5rem;
}
.info-table {
    width: 100%;
    border-collapse: collapse;
}
.info-table tr {
    border-bottom: 1px solid var(--border-color);
}
.info-table tr:last-child {
    border-bottom: none;
}
.info-table td {
    padding: 0.75rem 0;
}
.info-label {
    color: var(--text-secondary);
    width: 40%;
}
.info-value {
    color: var(--text-primary);
    font-weight: 500;
}
</style>
@endsection
