<!-- Bottom Navigation -->
<nav class="bottom-nav">
    <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fa-solid fa-home nav-icon"></i>
        <span class="nav-label">Home</span>
    </a>
    <a href="{{ route('wheelchairs.index') }}"
        class="nav-item {{ request()->routeIs('wheelchairs.*') ? 'active' : '' }}">
        <i class="fa-solid fa-wheelchair nav-icon"></i>
        <span class="nav-label">Browse</span>
    </a>
    <a href="{{ route('bookings.index') }}" class="nav-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
        <i class="fa-solid fa-receipt nav-icon"></i>
        <span class="nav-label">Bookings</span>
    </a>
    <a href="{{ route('profile.show') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="fa-solid fa-user nav-icon"></i>
        <span class="nav-label">Profile</span>
    </a>
</nav>