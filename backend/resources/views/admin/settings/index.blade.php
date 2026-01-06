@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    @forelse($settings as $group => $groupSettings)
    <div class="card mb-lg">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fa-solid fa-{{ $group === 'company' ? 'building' : ($group === 'booking' ? 'calendar' : ($group === 'payment' ? 'money-bill' : 'bell')) }}"></i>
                {{ ucfirst($group) }} Settings
            </h3>
        </div>
        <div class="card-body">
            <div class="grid-2" style="gap: 1.5rem;">
                @foreach($groupSettings as $setting)
                <div class="form-group">
                    <label class="form-label" for="{{ $setting->key }}">{{ $setting->label }}</label>
                    
                    @if($setting->type === 'textarea')
                        <textarea 
                            name="{{ $setting->key }}" 
                            id="{{ $setting->key }}" 
                            class="form-input" 
                            rows="3"
                        >{{ old($setting->key, $setting->value) }}</textarea>
                    @elseif($setting->type === 'boolean')
                        <div class="toggle-wrapper">
                            <label class="toggle-switch">
                                <input 
                                    type="checkbox" 
                                    name="{{ $setting->key }}" 
                                    id="{{ $setting->key }}" 
                                    value="1"
                                    {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}
                                >
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ $setting->value == '1' ? 'Enabled' : 'Disabled' }}</span>
                        </div>
                    @elseif($setting->type === 'number')
                        <input 
                            type="number" 
                            name="{{ $setting->key }}" 
                            id="{{ $setting->key }}" 
                            class="form-input" 
                            value="{{ old($setting->key, $setting->value) }}"
                        >
                    @elseif($setting->type === 'email')
                        <input 
                            type="email" 
                            name="{{ $setting->key }}" 
                            id="{{ $setting->key }}" 
                            class="form-input" 
                            value="{{ old($setting->key, $setting->value) }}"
                        >
                    @else
                        <input 
                            type="text" 
                            name="{{ $setting->key }}" 
                            id="{{ $setting->key }}" 
                            class="form-input" 
                            value="{{ old($setting->key, $setting->value) }}"
                        >
                    @endif
                    
                    @if($setting->description)
                        <div class="form-hint">{{ $setting->description }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @empty
    <div class="card">
        <div class="card-body text-center text-muted">
            <i class="fa-solid fa-cog" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <p>No settings configured yet.</p>
            <p style="font-size: 0.875rem;">Run <code>php artisan db:seed --class=SettingSeeder</code> to add default settings.</p>
        </div>
    </div>
    @endforelse

    @if($settings->count() > 0)
    <div class="flex gap-sm">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save"></i> Save Settings
        </button>
    </div>
    @endif
</form>

<style>
.form-hint {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}
.toggle-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--bg-tertiary);
    transition: 0.3s;
    border-radius: 24px;
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}
input:checked + .toggle-slider {
    background-color: var(--primary);
}
input:checked + .toggle-slider:before {
    transform: translateX(24px);
}
.toggle-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
}
</style>

<script>
document.querySelectorAll('.toggle-switch input').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        const label = this.closest('.toggle-wrapper').querySelector('.toggle-label');
        label.textContent = this.checked ? 'Enabled' : 'Disabled';
    });
});
</script>
@endsection
