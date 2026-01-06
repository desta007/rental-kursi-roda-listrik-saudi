@extends('admin.layouts.app')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Customer: {{ $user->name }}</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-input" 
                           value="{{ old('phone', $user->phone) }}" placeholder="+966xxxxxxxxx">
                    @error('phone')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" 
                           value="{{ old('email', $user->email) }}" placeholder="email@example.com">
                    @error('email')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Account Status *</label>
                    <select name="status" class="form-input form-select" required>
                        <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Verification Status *</label>
                    <select name="verification_status" class="form-input form-select" required>
                        <option value="pending" {{ old('verification_status', $user->verification_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ old('verification_status', $user->verification_status) == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" {{ old('verification_status', $user->verification_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('verification_status')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Identity Info (Read-only) -->
            <div class="info-box mb-lg">
                <h4 style="margin-bottom: 1rem; color: var(--text-secondary);"><i class="fa-solid fa-id-card"></i> Identity Information (Read-only)</h4>
                <div class="grid-3">
                    <div>
                        <label class="form-label text-muted">ID Type</label>
                        <div style="font-weight: 500;">{{ ucfirst($user->identity_type ?? 'Not provided') }}</div>
                    </div>
                    <div>
                        <label class="form-label text-muted">ID Number</label>
                        <div style="font-weight: 500;">{{ $user->identity_number ?? 'Not provided' }}</div>
                    </div>
                    <div>
                        <label class="form-label text-muted">ID Photo</label>
                        @if($user->identity_photo)
                            <a href="{{ asset('storage/' . $user->identity_photo) }}" target="_blank" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-image"></i> View Photo
                            </a>
                        @else
                            <div class="text-muted">Not uploaded</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Stats (Read-only) -->
            <div class="info-box mb-lg">
                <h4 style="margin-bottom: 1rem; color: var(--text-secondary);"><i class="fa-solid fa-chart-bar"></i> Account Statistics</h4>
                <div class="grid-3">
                    <div>
                        <label class="form-label text-muted">Total Bookings</label>
                        <div style="font-weight: 500; font-size: 1.25rem;">{{ $user->bookings->count() }}</div>
                    </div>
                    <div>
                        <label class="form-label text-muted">Registered</label>
                        <div style="font-weight: 500;">{{ $user->created_at?->format('d M Y') }}</div>
                    </div>
                    <div>
                        <label class="form-label text-muted">Last Updated</label>
                        <div style="font-weight: 500;">{{ $user->updated_at?->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="flex gap-sm">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Update Customer
                </button>
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
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
@endsection
