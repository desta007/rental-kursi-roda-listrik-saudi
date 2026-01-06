@extends('admin.layouts.app')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Booking: {{ $booking->booking_code }}</h3>
        <div class="flex gap-sm">
            <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-primary">
                <i class="fa-solid fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Status Badge -->
        <div class="mb-lg">
            <span class="badge badge-{{ $booking->status === 'active' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'completed' ? 'info' : 'danger')) }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                {{ ucfirst($booking->status) }}
            </span>
        </div>

        <div class="grid-2 mb-lg" style="gap: 2rem;">
            <!-- Customer Info -->
            <div class="info-section">
                <h4 style="margin-bottom: 1rem; color: var(--text-primary);"><i class="fa-solid fa-user"></i> Customer Information</h4>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Name</td>
                        <td class="info-value">{{ $booking->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Phone</td>
                        <td class="info-value">{{ $booking->user->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Email</td>
                        <td class="info-value">{{ $booking->user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">ID Type</td>
                        <td class="info-value">{{ ucfirst($booking->user->identity_type ?? 'N/A') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Wheelchair Info -->
            <div class="info-section">
                <h4 style="margin-bottom: 1rem; color: var(--text-primary);"><i class="fa-solid fa-wheelchair"></i> Wheelchair Information</h4>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Code</td>
                        <td class="info-value">{{ $booking->wheelchair->code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Type</td>
                        <td class="info-value">{{ $booking->wheelchair->wheelchairType->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Brand/Model</td>
                        <td class="info-value">{{ ($booking->wheelchair->brand ?? '') . ' ' . ($booking->wheelchair->model ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Station</td>
                        <td class="info-value">{{ $booking->station->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="grid-2 mb-lg" style="gap: 2rem;">
            <!-- Booking Details -->
            <div class="info-section">
                <h4 style="margin-bottom: 1rem; color: var(--text-primary);"><i class="fa-solid fa-calendar"></i> Booking Details</h4>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Start Date</td>
                        <td class="info-value">{{ $booking->start_date?->format('d M Y, H:i') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">End Date</td>
                        <td class="info-value">{{ $booking->end_date?->format('d M Y, H:i') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Duration</td>
                        <td class="info-value">{{ $booking->days }} days</td>
                    </tr>
                    <tr>
                        <td class="info-label">Pickup Type</td>
                        <td class="info-value">{{ ucfirst($booking->pickup_type ?? 'N/A') }}</td>
                    </tr>
                    @if($booking->pickup_type === 'delivery')
                    <tr>
                        <td class="info-label">Delivery Address</td>
                        <td class="info-value">{{ $booking->delivery_address ?? 'N/A' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="info-label">Picked Up At</td>
                        <td class="info-value">{{ $booking->picked_up_at?->format('d M Y, H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Returned At</td>
                        <td class="info-value">{{ $booking->returned_at?->format('d M Y, H:i') ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Payment Info -->
            <div class="info-section">
                <h4 style="margin-bottom: 1rem; color: var(--text-primary);"><i class="fa-solid fa-money-bill"></i> Payment Details</h4>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Rental Amount</td>
                        <td class="info-value">SAR {{ number_format($booking->rental_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Delivery Fee</td>
                        <td class="info-value">SAR {{ number_format($booking->delivery_fee, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Discount</td>
                        <td class="info-value">- SAR {{ number_format($booking->discount_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">VAT</td>
                        <td class="info-value">SAR {{ number_format($booking->vat_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Deposit</td>
                        <td class="info-value">SAR {{ number_format($booking->deposit_amount, 2) }}</td>
                    </tr>
                    <tr style="font-weight: 600; border-top: 2px solid var(--border-color);">
                        <td class="info-label">Total Amount</td>
                        <td class="info-value" style="color: var(--primary);">SAR {{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                    @if($booking->promo_code)
                    <tr>
                        <td class="info-label">Promo Code</td>
                        <td class="info-value"><span class="badge badge-info">{{ $booking->promo_code }}</span></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        @if($booking->notes)
        <div class="info-section mb-lg">
            <h4 style="margin-bottom: 1rem; color: var(--text-primary);"><i class="fa-solid fa-note-sticky"></i> Notes</h4>
            <p style="background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius);">{{ $booking->notes }}</p>
        </div>
        @endif

        @if($booking->cancellation_reason)
        <div class="info-section mb-lg">
            <h4 style="margin-bottom: 1rem; color: var(--danger);"><i class="fa-solid fa-ban"></i> Cancellation Reason</h4>
            <p style="background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: var(--radius); color: var(--danger);">{{ $booking->cancellation_reason }}</p>
        </div>
        @endif

        <!-- Payments History -->
        @if($booking->payments && $booking->payments->count() > 0)
        <div class="info-section">
            <h4 style="margin-bottom: 1rem; color: var(--text-primary);"><i class="fa-solid fa-credit-card"></i> Payment History</h4>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->payments as $payment)
                        <tr>
                            <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                            <td>{{ ucfirst($payment->type ?? 'payment') }}</td>
                            <td>SAR {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->method ?? 'N/A') }}</td>
                            <td>
                                <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
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
