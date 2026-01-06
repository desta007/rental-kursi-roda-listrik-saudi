@extends('admin.layouts.app')

@section('title', isset($wheelchair) ? 'Edit Wheelchair' : 'Add Wheelchair')
@section('page-title', isset($wheelchair) ? 'Edit Wheelchair' : 'Add Wheelchair')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ isset($wheelchair) ? 'Edit Wheelchair' : 'New Wheelchair' }}</h3>
        <a href="{{ route('admin.wheelchairs.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ isset($wheelchair) ? route('admin.wheelchairs.update', $wheelchair) : route('admin.wheelchairs.store') }}" method="POST">
            @csrf
            @if(isset($wheelchair))
                @method('PUT')
            @endif

            <div class="grid-3 mb-lg">
                <div class="form-group">
                    <label class="form-label">Code *</label>
                    <input type="text" name="code" class="form-input" placeholder="MK-001"
                           value="{{ old('code', $wheelchair->code ?? '') }}" required>
                    @error('code')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Wheelchair Type *</label>
                    <select name="wheelchair_type_id" class="form-input form-select" required>
                        <option value="">Select Type</option>
                        @foreach($wheelchairTypes as $type)
                            <option value="{{ $type->id }}" {{ old('wheelchair_type_id', $wheelchair->wheelchair_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('wheelchair_type_id')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Station *</label>
                    <select name="station_id" class="form-input form-select" required>
                        <option value="">Select Station</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ old('station_id', $wheelchair->station_id ?? '') == $station->id ? 'selected' : '' }}>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('station_id')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Brand *</label>
                    <input type="text" name="brand" class="form-input"
                           value="{{ old('brand', $wheelchair->brand ?? '') }}" required>
                    @error('brand')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Model *</label>
                    <input type="text" name="model" class="form-input"
                           value="{{ old('model', $wheelchair->model ?? '') }}" required>
                    @error('model')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="grid-2 mb-lg">
                <div class="form-group">
                    <label class="form-label">Battery Capacity (%) *</label>
                    <input type="number" name="battery_capacity" class="form-input" min="0" max="100"
                           value="{{ old('battery_capacity', $wheelchair->battery_capacity ?? 100) }}" required>
                    @error('battery_capacity')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input form-select" required>
                        <option value="available" {{ old('status', $wheelchair->status ?? 'available') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="rented" {{ old('status', $wheelchair->status ?? '') == 'rented' ? 'selected' : '' }}>Rented</option>
                        <option value="maintenance" {{ old('status', $wheelchair->status ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="retired" {{ old('status', $wheelchair->status ?? '') == 'retired' ? 'selected' : '' }}>Retired</option>
                    </select>
                    @error('status')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group mb-lg">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-input" rows="3">{{ old('notes', $wheelchair->notes ?? '') }}</textarea>
                @error('notes')<div class="error-message">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i>
                {{ isset($wheelchair) ? 'Update Wheelchair' : 'Add Wheelchair' }}
            </button>
        </form>
    </div>
</div>
@endsection
