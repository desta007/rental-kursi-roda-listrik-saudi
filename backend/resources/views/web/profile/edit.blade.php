@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="booking-header">
        <a href="{{ route('profile.show') }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="booking-header-title">Edit Profile</h1>
    </div>

    <div class="screen-content">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="alert alert-danger mb-md"
                    style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); border-radius: var(--radius-md); padding: var(--spacing-md); color: var(--error);">
                    <ul style="margin: 0; padding-left: var(--spacing-md);">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-input" value="{{ $user->phone }}" disabled>
                <small class="text-muted">Phone number cannot be changed</small>
            </div>

            <div class="form-group">
                <label class="form-label">Email (Optional)</label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}"
                    placeholder="your@email.com">
            </div>

            <div class="form-group">
                <label class="form-label">Identity Type</label>
                <select name="identity_type" class="form-input">
                    <option value="national_id" {{ old('identity_type', $user->identity_type) == 'national_id' ? 'selected' : '' }}>National ID</option>
                    <option value="passport" {{ old('identity_type', $user->identity_type) == 'passport' ? 'selected' : '' }}>
                        Passport</option>
                    <option value="iqama" {{ old('identity_type', $user->identity_type) == 'iqama' ? 'selected' : '' }}>Iqama
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Identity Number</label>
                <input type="text" name="identity_number" class="form-input"
                    value="{{ old('identity_number', $user->identity_number) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Preferred Language</label>
                <select name="language" class="form-input">
                    <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>English</option>
                    <option value="ar" {{ old('language', $user->language) == 'ar' ? 'selected' : '' }}>العربية (Arabic)
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-full mt-lg">
                <i class="fa-solid fa-save"></i>
                Save Changes
            </button>
        </form>
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
    </style>
@endpush