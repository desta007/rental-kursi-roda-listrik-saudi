@extends('admin.layouts.app')

@section('title', isset($station) ? 'Edit Station' : 'Create Station')
@section('page-title', isset($station) ? 'Edit Station' : 'Create Station')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ isset($station) ? 'Edit Station' : 'New Station' }}</h3>
        <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ isset($station) ? route('admin.stations.update', $station) : route('admin.stations.store') }}" method="POST">
            @csrf
            @if(isset($station))
                @method('PUT')
            @endif

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Name (English) *</label>
                    <input type="text" name="name" class="form-input" 
                           value="{{ old('name', $station->name ?? '') }}" required>
                    @error('name')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Name (Arabic) *</label>
                    <input type="text" name="name_ar" class="form-input" dir="rtl"
                           value="{{ old('name_ar', $station->name_ar ?? '') }}" required>
                    @error('name_ar')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group mb-lg">
                <label class="form-label">City *</label>
                <input type="text" name="city" class="form-input" 
                       value="{{ old('city', $station->city ?? 'Mecca') }}" required>
                @error('city')<div class="error-message">{{ $message }}</div>@enderror
            </div>

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Address (English) *</label>
                    <textarea name="address" class="form-input" rows="2" required>{{ old('address', $station->address ?? '') }}</textarea>
                    @error('address')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Address (Arabic) *</label>
                    <textarea name="address_ar" class="form-input" rows="2" dir="rtl" required>{{ old('address_ar', $station->address_ar ?? '') }}</textarea>
                    @error('address_ar')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Latitude *</label>
                    <input type="number" name="latitude" class="form-input" step="0.00000001"
                           value="{{ old('latitude', $station->latitude ?? '21.4225') }}" required>
                    @error('latitude')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Longitude *</label>
                    <input type="number" name="longitude" class="form-input" step="0.00000001"
                           value="{{ old('longitude', $station->longitude ?? '39.8262') }}" required>
                    @error('longitude')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Operating Hours *</label>
                    <input type="text" name="operating_hours" class="form-input" placeholder="e.g., 06:00 - 22:00"
                           value="{{ old('operating_hours', $station->operating_hours ?? '06:00 - 22:00') }}" required>
                    @error('operating_hours')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Phone *</label>
                    <input type="text" name="contact_phone" class="form-input" placeholder="+966..."
                           value="{{ old('contact_phone', $station->contact_phone ?? '') }}" required>
                    @error('contact_phone')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group mb-lg">
                <label class="remember-check">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ old('is_active', $station->is_active ?? true) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i>
                {{ isset($station) ? 'Update Station' : 'Create Station' }}
            </button>
        </form>
    </div>
</div>
@endsection
