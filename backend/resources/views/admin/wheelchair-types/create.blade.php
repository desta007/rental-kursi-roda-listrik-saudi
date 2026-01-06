@extends('admin.layouts.app')

@section('title', isset($wheelchairType) ? 'Edit Type' : 'Add Type')
@section('page-title', isset($wheelchairType) ? 'Edit Wheelchair Type' : 'Add Wheelchair Type')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ isset($wheelchairType) ? 'Edit Wheelchair Type' : 'New Wheelchair Type' }}</h3>
        <a href="{{ route('admin.wheelchair-types.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ isset($wheelchairType) ? route('admin.wheelchair-types.update', $wheelchairType) : route('admin.wheelchair-types.store') }}" method="POST">
            @csrf
            @if(isset($wheelchairType))
                @method('PUT')
            @endif

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Name (English) *</label>
                    <input type="text" name="name" class="form-input"
                           value="{{ old('name', $wheelchairType->name ?? '') }}" required>
                    @error('name')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Name (Arabic) *</label>
                    <input type="text" name="name_ar" class="form-input" dir="rtl"
                           value="{{ old('name_ar', $wheelchairType->name_ar ?? '') }}" required>
                    @error('name_ar')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Description (English)</label>
                    <textarea name="description" class="form-input" rows="2">{{ old('description', $wheelchairType->description ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Description (Arabic)</label>
                    <textarea name="description_ar" class="form-input" rows="2" dir="rtl">{{ old('description_ar', $wheelchairType->description_ar ?? '') }}</textarea>
                </div>
            </div>

            <h4 style="margin-bottom: var(--spacing-md);">Pricing (SAR)</h4>
            <div class="grid-2 mb-lg" style="grid-template-columns: repeat(4, 1fr);">
                <div class="form-group">
                    <label class="form-label">Daily Rate *</label>
                    <input type="number" name="daily_rate" class="form-input" step="0.01" min="0"
                           value="{{ old('daily_rate', $wheelchairType->daily_rate ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Weekly Rate *</label>
                    <input type="number" name="weekly_rate" class="form-input" step="0.01" min="0"
                           value="{{ old('weekly_rate', $wheelchairType->weekly_rate ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Monthly Rate *</label>
                    <input type="number" name="monthly_rate" class="form-input" step="0.01" min="0"
                           value="{{ old('monthly_rate', $wheelchairType->monthly_rate ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deposit *</label>
                    <input type="number" name="deposit_amount" class="form-input" step="0.01" min="0"
                           value="{{ old('deposit_amount', $wheelchairType->deposit_amount ?? '') }}" required>
                </div>
            </div>

            <h4 style="margin-bottom: var(--spacing-md);">Specifications</h4>
            <div class="grid-3 mb-lg">
                <div class="form-group">
                    <label class="form-label">Battery Range (km) *</label>
                    <input type="number" name="battery_range_km" class="form-input" min="1"
                           value="{{ old('battery_range_km', $wheelchairType->battery_range_km ?? 25) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Max Weight (kg) *</label>
                    <input type="number" name="max_weight_kg" class="form-input" min="1"
                           value="{{ old('max_weight_kg', $wheelchairType->max_weight_kg ?? 120) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Max Speed (km/h) *</label>
                    <input type="number" name="max_speed_kmh" class="form-input" min="1"
                           value="{{ old('max_speed_kmh', $wheelchairType->max_speed_kmh ?? 6) }}" required>
                </div>
            </div>

            <div class="form-group mb-lg">
                <label class="remember-check">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ old('is_active', $wheelchairType->is_active ?? true) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i>
                {{ isset($wheelchairType) ? 'Update Type' : 'Create Type' }}
            </button>
        </form>
    </div>
</div>
@endsection
