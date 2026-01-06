<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - MobilityKSA</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fa-solid fa-wheelchair"></i>
                </div>
                <div class="sidebar-brand">Mobility<span>KSA</span></div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-check"></i>
                        Bookings
                        @if(isset($pendingBookingsCount) && $pendingBookingsCount > 0)
                            <span class="nav-item-badge">{{ $pendingBookingsCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.wheelchairs.index') }}" class="nav-item {{ request()->routeIs('admin.wheelchairs.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-wheelchair"></i>
                        Wheelchairs
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        Customers
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    <a href="{{ route('admin.stations.index') }}" class="nav-item {{ request()->routeIs('admin.stations.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-location-dot"></i>
                        Stations
                    </a>
                    <a href="{{ route('admin.wheelchair-types.index') }}" class="nav-item {{ request()->routeIs('admin.wheelchair-types.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-tags"></i>
                        Wheelchair Types
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-cog"></i>
                        Settings
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="user-avatar">{{ substr(auth()->user()->name ?? 'A', 0, 2) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="user-role">Administrator</div>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; cursor: pointer; color: var(--text-muted);" title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="header-right">
                    <div class="header-search" id="globalSearch">
                        <i class="fa-solid fa-search" style="color: var(--text-muted);"></i>
                        <input type="text" id="searchInput" placeholder="Search bookings, users, wheelchairs..." autocomplete="off">
                        <div class="search-results" id="searchResults"></div>
                    </div>
                    <div class="header-icon-btn" id="notificationBtn">
                        <i class="fa-solid fa-bell"></i>
                        @php
                            $unreadCount = \App\Models\Booking::where('status', 'pending')
                                ->whereNull('admin_read_at')
                                ->count();
                            $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="notification-dot">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <span>Notifications</span>
                                @if($unreadCount > 0)
                                    <span class="badge badge-warning">{{ $unreadCount }} unread</span>
                                @elseif($pendingCount > 0)
                                    <span class="badge badge-info">{{ $pendingCount }} pending</span>
                                @endif
                            </div>
                            <div class="notification-list">
                                @php
                                    $recentPending = \App\Models\Booking::with('user')
                                        ->where('status', 'pending')
                                        ->latest()
                                        ->take(5)
                                        ->get();
                                @endphp
                                @forelse($recentPending as $booking)
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="notification-item {{ $booking->admin_read_at ? 'read' : 'unread' }}">
                                    <div class="notification-icon {{ $booking->admin_read_at ? '' : 'unread' }}">
                                        <i class="fa-solid fa-calendar-plus"></i>
                                    </div>
                                    <div class="notification-content">
                                        <div class="notification-title">
                                            New Booking Request
                                            @if(!$booking->admin_read_at)
                                                <span class="unread-badge"></span>
                                            @endif
                                        </div>
                                        <div class="notification-text">{{ $booking->user->name ?? 'Unknown' }} - {{ $booking->booking_code }}</div>
                                        <div class="notification-time">{{ $booking->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                                @empty
                                <div class="notification-empty">
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>No pending notifications</span>
                                </div>
                                @endforelse
                            </div>
                            @if($pendingCount > 5)
                            <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="notification-footer">
                                View all {{ $pendingCount }} pending bookings
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <div class="page-content">
                @if(session('success'))
                    <div class="alert alert-success mb-lg" style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--success); border-radius: var(--radius-md); padding: var(--spacing-md); color: var(--success);">
                        <i class="fa-solid fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger mb-lg" style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); border-radius: var(--radius-md); padding: var(--spacing-md); color: var(--error);">
                        <i class="fa-solid fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global Search
        let searchTimeout;
        $('#searchInput').on('input', function() {
            const query = $(this).val().trim();
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                $('#searchResults').hide().empty();
                return;
            }
            
            searchTimeout = setTimeout(function() {
                $.get('{{ route("admin.search") }}', { q: query }, function(data) {
                    let html = '';
                    
                    if (data.bookings && data.bookings.length > 0) {
                        html += '<div class="search-section"><div class="search-section-title">Bookings</div>';
                        data.bookings.forEach(function(item) {
                            html += `<a href="/admin/bookings/${item.id}" class="search-item">
                                <i class="fa-solid fa-calendar-check"></i>
                                <div>
                                    <div>${item.booking_code}</div>
                                    <div class="text-muted">${item.user_name} - ${item.status}</div>
                                </div>
                            </a>`;
                        });
                        html += '</div>';
                    }
                    
                    if (data.users && data.users.length > 0) {
                        html += '<div class="search-section"><div class="search-section-title">Customers</div>';
                        data.users.forEach(function(item) {
                            html += `<a href="/admin/users/${item.id}" class="search-item">
                                <i class="fa-solid fa-user"></i>
                                <div>
                                    <div>${item.name}</div>
                                    <div class="text-muted">${item.phone || item.email || ''}</div>
                                </div>
                            </a>`;
                        });
                        html += '</div>';
                    }
                    
                    if (data.wheelchairs && data.wheelchairs.length > 0) {
                        html += '<div class="search-section"><div class="search-section-title">Wheelchairs</div>';
                        data.wheelchairs.forEach(function(item) {
                            html += `<a href="/admin/wheelchairs/${item.id}/edit" class="search-item">
                                <i class="fa-solid fa-wheelchair"></i>
                                <div>
                                    <div>${item.code}</div>
                                    <div class="text-muted">${item.type_name} - ${item.status}</div>
                                </div>
                            </a>`;
                        });
                        html += '</div>';
                    }
                    
                    if (html === '') {
                        html = '<div class="search-empty">No results found</div>';
                    }
                    
                    $('#searchResults').html(html).show();
                });
            }, 300);
        });

        // Close search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#globalSearch').length) {
                $('#searchResults').hide();
            }
        });

        // Notification dropdown toggle
        $('#notificationBtn > i, #notificationBtn > .notification-dot').on('click', function(e) {
            e.stopPropagation();
            $('#notificationDropdown').toggleClass('show');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#notificationBtn').length) {
                $('#notificationDropdown').removeClass('show');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

