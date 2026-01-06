@extends('admin.layouts.app')

@section('title', 'Edit Booking')
@section('page-title', 'Edit Booking')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Booking: {{ $booking->booking_code }}</h3>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Current Status Display -->
            <div class="mb-lg">
                <label class="form-label">Current Status</label>
                <div>
                    <span class="badge badge-{{ $booking->status === 'active' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'completed' ? 'info' : 'danger')) }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            <!-- Booking Info Summary (Read-only) -->
            <div class="info-box mb-lg">
                <div class="grid-3">
                    <div>
                        <label class="form-label text-muted">Customer</label>
                        <div style="font-weight: 500;">{{ $booking->user->name ?? 'N/A' }}</div>
                        <div class="text-muted" style="font-size: 0.875rem;">{{ $booking->user->phone ?? '' }}</div>
                    </div>
                    <div>
                        <label class="form-label text-muted">Wheelchair</label>
                        <div style="font-weight: 500;">{{ $booking->wheelchair->code ?? 'N/A' }}</div>
                        <div class="text-muted" style="font-size: 0.875rem;">{{ $booking->wheelchair->wheelchairType->name ?? '' }}</div>
                    </div>
                    <div>
                        <label class="form-label text-muted">Dates</label>
                        <div style="font-weight: 500;">{{ $booking->start_date?->format('d M Y') }} - {{ $booking->end_date?->format('d M Y') }}</div>
                        <div class="text-muted" style="font-size: 0.875rem;">{{ $booking->days }} days | SAR {{ number_format($booking->total_amount) }}</div>
                    </div>
                </div>
            </div>

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input form-select" required>
                        <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="active" {{ old('status', $booking->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group mb-lg">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-input" rows="3" placeholder="Add any additional notes...">{{ old('notes', $booking->notes) }}</textarea>
                @error('notes')<div class="error-message">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-lg" id="cancellation-reason-group" style="{{ old('status', $booking->status) == 'cancelled' ? '' : 'display: none;' }}">
                <label class="form-label">Cancellation Reason *</label>
                <textarea name="cancellation_reason" class="form-input" rows="3" placeholder="Please provide a reason for cancellation...">{{ old('cancellation_reason', $booking->cancellation_reason) }}</textarea>
                @error('cancellation_reason')<div class="error-message">{{ $message }}</div>@enderror
            </div>

            <div class="flex gap-sm">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Update Booking
                </button>
                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary">
                    View Details
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.info-box {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    padding: 1.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="status"]');
    const cancellationGroup = document.getElementById('cancellation-reason-group');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'cancelled') {
            cancellationGroup.style.display = 'block';
        } else {
            cancellationGroup.style.display = 'none';
        }
    });
});
</script>
@endsection
