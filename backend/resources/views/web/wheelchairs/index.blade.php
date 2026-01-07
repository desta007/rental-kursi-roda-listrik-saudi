@extends('layouts.app')

@section('title', 'Browse Wheelchairs')

@section('content')
    <!-- Filter Header -->
    <div class="filter-header">
        <a href="{{ route('home') }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <form action="{{ route('wheelchairs.index') }}" method="GET" class="search-input-wrapper" style="flex: 1;">
            <i class="fa-solid fa-search" style="color: var(--text-muted);"></i>
            <input type="text" name="search" placeholder="Search wheelchairs..." value="{{ request('search') }}">
        </form>
        <div class="filter-btn" onclick="openFilter()">
            <i class="fa-solid fa-sliders"></i>
            @if(request('type') || request('station'))
                <span class="filter-indicator">!</span>
            @endif
        </div>
    </div>

    <!-- Category Pills -->
    <div style="padding: var(--spacing-md);">
        <div class="category-pills">
            <a href="{{ route('wheelchairs.index') }}" class="category-pill {{ !request('type') ? 'active' : '' }}">All</a>
            @foreach($types as $type)
                <a href="{{ route('wheelchairs.index', ['type' => $type->id]) }}"
                    class="category-pill {{ request('type') == $type->id ? 'active' : '' }}">
                    {{ $type->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Location Filter Indicator -->
    @if($selectedLocation)
        <div style="padding: 0 var(--spacing-md); margin-bottom: var(--spacing-sm);">
            <div class="location-filter-badge">
                <i class="fa-solid fa-location-dot"></i>
                <span>Showing results at <strong>{{ $selectedLocation->name }}</strong></span>
                <a href="{{ route('home') }}" class="change-location-link">Change</a>
            </div>
        </div>
    @endif

    <!-- Results Info -->
    <div class="results-info">
        <span class="results-count">{{ $wheelchairs->total() }} wheelchairs available</span>
        <div class="sort-btn">
            <i class="fa-solid fa-arrow-up-short-wide"></i>
            Price: {{ request('sort') == 'price_high' ? 'High to Low' : 'Low to High' }}
        </div>
    </div>

    <!-- Wheelchair List -->
    <div class="screen-content" style="padding-top: 0; padding-bottom: 100px;">
        <div class="wheelchair-list">
            @forelse($wheelchairs as $wheelchair)
                <div class="wheelchair-card-full" onclick="window.location.href='{{ route('wheelchairs.show', $wheelchair) }}'">
                    <div class="wheelchair-card-image">
                        @if($wheelchair->wheelchairType->image)
                            <img src="{{ asset('storage/' . $wheelchair->wheelchairType->image) }}"
                                alt="{{ $wheelchair->wheelchairType->name }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=400&h=300&fit=crop"
                                alt="Wheelchair">
                        @endif
                        <div class="wheelchair-badge-overlay">
                            <span class="status-badge status-available">Available</span>
                        </div>
                        <div class="wheelchair-code-badge">{{ $wheelchair->code }}</div>
                        <div class="favorite-btn" onclick="event.stopPropagation(); this.classList.toggle('active')">
                            <i class="fa-solid fa-heart"></i>
                        </div>
                    </div>
                    <div class="wheelchair-card-body">
                        <h3 class="wheelchair-card-title">{{ $wheelchair->wheelchairType->name }}</h3>
                        <p class="wheelchair-card-brand">
                            <i class="fa-solid fa-industry"></i>
                            {{ $wheelchair->brand ?? 'N/A' }} - {{ $wheelchair->model ?? 'N/A' }}
                        </p>
                        <div class="wheelchair-card-specs">
                            <div class="spec-item">
                                <i class="fa-solid fa-battery-full"></i>
                                <span>{{ $wheelchair->wheelchairType->battery_range_km }}km range</span>
                            </div>
                            <div class="spec-item">
                                <i class="fa-solid fa-weight-hanging"></i>
                                <span>{{ $wheelchair->wheelchairType->max_weight_kg }}kg max</span>
                            </div>
                            <div class="spec-item">
                                <i class="fa-solid fa-gauge-high"></i>
                                <span>{{ $wheelchair->wheelchairType->max_speed_kmh }} km/h</span>
                            </div>
                        </div>
                        <div class="wheelchair-card-footer">
                            <div class="wheelchair-card-price">
                                <span class="card-price-amount">SAR
                                    {{ number_format($wheelchair->wheelchairType->daily_rate, 0) }}</span>
                                <span class="card-price-unit">/ day</span>
                            </div>
                            <div class="book-now-btn">Book Now</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fa-solid fa-wheelchair"></i>
                    </div>
                    <h3 class="empty-state-title">No wheelchairs found</h3>
                    <p class="empty-state-text">Try adjusting your filters or search terms.</p>
                    <a href="{{ route('wheelchairs.index') }}" class="btn btn-primary">Clear Filters</a>
                </div>
            @endforelse
        </div>

        @if($wheelchairs->hasPages())
            <div style="padding: var(--spacing-md);">
                {{ $wheelchairs->links() }}
            </div>
        @endif
    </div>

    <!-- Filter Modal -->
    <div class="modal-overlay" id="modal-overlay" onclick="closeFilter()"></div>
    <div class="filter-modal" id="filter-modal">
        <div class="filter-modal-header">
            <h3 class="filter-modal-title">Filter</h3>
            <div class="close-modal" onclick="closeFilter()">
                <i class="fa-solid fa-times"></i>
            </div>
        </div>
        <form action="{{ route('wheelchairs.index') }}" method="GET">
            <div class="filter-modal-body">
                <div class="filter-section">
                    <h4 class="filter-section-title">Wheelchair Type</h4>
                    <div class="filter-chips">
                        <label class="filter-chip {{ !request('type') ? 'active' : '' }}">
                            <input type="radio" name="type" value="" style="display: none;" {{ !request('type') ? 'checked' : '' }}>
                            All Types
                        </label>
                        @foreach($types as $type)
                            <label class="filter-chip {{ request('type') == $type->id ? 'active' : '' }}">
                                <input type="radio" name="type" value="{{ $type->id }}" style="display: none;" {{ request('type') == $type->id ? 'checked' : '' }}>
                                {{ $type->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="filter-section">
                    <h4 class="filter-section-title">Pickup Station</h4>
                    <div class="filter-chips">
                        <label class="filter-chip {{ !request('station') ? 'active' : '' }}">
                            <input type="radio" name="station" value="" style="display: none;" {{ !request('station') ? 'checked' : '' }}>
                            Any Station
                        </label>
                        @foreach($stations as $station)
                            <label class="filter-chip {{ request('station') == $station->id ? 'active' : '' }}">
                                <input type="radio" name="station" value="{{ $station->id }}" style="display: none;" {{ request('station') == $station->id ? 'checked' : '' }}>
                                {{ $station->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="filter-section">
                    <h4 class="filter-section-title">Sort By</h4>
                    <div class="filter-chips">
                        <label class="filter-chip {{ request('sort', 'price_low') == 'price_low' ? 'active' : '' }}">
                            <input type="radio" name="sort" value="price_low" style="display: none;" {{ request('sort', 'price_low') == 'price_low' ? 'checked' : '' }}>
                            Price: Low to High
                        </label>
                        <label class="filter-chip {{ request('sort') == 'price_high' ? 'active' : '' }}">
                            <input type="radio" name="sort" value="price_high" style="display: none;" {{ request('sort') == 'price_high' ? 'checked' : '' }}>
                            Price: High to Low
                        </label>
                    </div>
                </div>
            </div>
            <div class="filter-modal-footer">
                <a href="{{ route('wheelchairs.index') }}" class="btn btn-secondary">Reset</a>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .filter-header {
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
            cursor: pointer;
            text-decoration: none;
        }

        .search-input-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-md);
            background: var(--bg-input);
            border-radius: var(--radius-md);
        }

        .search-input-wrapper input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .search-input-wrapper input:focus {
            outline: none;
        }

        .filter-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            border-radius: var(--radius-md);
            color: white;
            cursor: pointer;
            position: relative;
        }

        .filter-indicator {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 16px;
            height: 16px;
            background: var(--error);
            border-radius: 50%;
            font-size: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .results-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-md);
        }

        .results-count {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .sort-btn {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            color: var(--primary-light);
            font-size: 0.875rem;
            cursor: pointer;
        }

        .wheelchair-list {
            padding: 0 var(--spacing-md);
        }

        .wheelchair-card-full {
            display: flex;
            flex-direction: column;
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            margin-bottom: var(--spacing-md);
            overflow: hidden;
            cursor: pointer;
            transition: all var(--transition-normal);
        }

        .wheelchair-card-full:hover {
            transform: translateY(-2px);
            border-color: var(--primary-light);
            box-shadow: var(--shadow-lg);
        }

        .wheelchair-card-image {
            height: 180px;
            background: var(--bg-input);
            position: relative;
        }

        .wheelchair-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .wheelchair-badge-overlay {
            position: absolute;
            top: var(--spacing-sm);
            left: var(--spacing-sm);
        }

        .favorite-btn {
            position: absolute;
            top: var(--spacing-sm);
            right: var(--spacing-sm);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            color: white;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .favorite-btn.active {
            color: var(--error);
        }

        .wheelchair-card-body {
            padding: var(--spacing-md);
        }

        .wheelchair-card-title {
            font-weight: 600;
            font-size: 1.125rem;
            margin-bottom: var(--spacing-xs);
        }

        .wheelchair-card-brand {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .wheelchair-card-brand i {
            color: var(--primary-light);
        }

        .wheelchair-code-badge {
            position: absolute;
            bottom: var(--spacing-sm);
            right: var(--spacing-sm);
            padding: 4px 8px;
            background: rgba(0, 0, 0, 0.7);
            color: var(--secondary);
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            font-family: monospace;
        }

        .wheelchair-card-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: var(--spacing-md);
        }

        .wheelchair-card-specs {
            display: flex;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-md);
        }

        .spec-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .spec-item i {
            color: var(--primary-light);
        }

        .wheelchair-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .wheelchair-card-price {
            display: flex;
            align-items: baseline;
            gap: var(--spacing-xs);
        }

        .card-price-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .card-price-unit {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .book-now-btn {
            padding: var(--spacing-sm) var(--spacing-lg);
            background: var(--gradient-primary);
            border-radius: var(--radius-md);
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        /* Filter Modal */
        .filter-modal {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            max-height: 80vh;
            background: var(--bg-secondary);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            z-index: 1000;
            display: none;
        }

        .filter-modal.active {
            display: block;
        }

        .filter-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-md);
            border-bottom: 1px solid var(--border);
        }

        .filter-modal-title {
            font-weight: 600;
            font-size: 1.125rem;
        }

        .close-modal {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-input);
            border-radius: 50%;
            cursor: pointer;
        }

        .filter-modal-body {
            padding: var(--spacing-md);
            overflow-y: auto;
            max-height: calc(80vh - 140px);
        }

        .filter-section {
            margin-bottom: var(--spacing-lg);
        }

        .filter-section-title {
            font-weight: 600;
            margin-bottom: var(--spacing-md);
        }

        .filter-chips {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-sm);
        }

        .filter-chip {
            padding: var(--spacing-sm) var(--spacing-md);
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .filter-chip.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .filter-modal-footer {
            display: flex;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            border-top: 1px solid var(--border);
        }

        .filter-modal-footer .btn {
            flex: 1;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .modal-overlay.active {
            display: block;
        }

        .location-filter-badge {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-md);
            background: rgba(27, 77, 62, 0.2);
            border: 1px solid var(--primary);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            color: var(--primary-light);
        }

        .location-filter-badge i {
            color: var(--primary);
        }

        .location-filter-badge span {
            flex: 1;
        }

        .change-location-link {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function openFilter() {
            document.getElementById('modal-overlay').classList.add('active');
            document.getElementById('filter-modal').classList.add('active');
        }

        function closeFilter() {
            document.getElementById('modal-overlay').classList.remove('active');
            document.getElementById('filter-modal').classList.remove('active');
        }

        // Filter chip toggle
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.addEventListener('click', function () {
                const parent = this.parentElement;
                parent.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Category pill toggle
        document.querySelectorAll('.category-pill').forEach(pill => {
            pill.addEventListener('click', function () {
                document.querySelectorAll('.category-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
@endpush