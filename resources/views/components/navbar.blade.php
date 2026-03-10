<nav class="admin-navbar">

    <!-- Logo -->
    <div class="nav-logo">
        Admin Panel
    </div>

    <!-- Mobile Toggle -->
    <div class="nav-toggle" id="navToggle">
        ☰
    </div>

    <!-- Navigation Menu -->
    <ul class="nav-menu" id="navMenu">

        <li class="{{ request()->routeIs('reservations.*') ? 'active' : '' }}">
            <a href="{{ route('reservations.index') }}">
                <i class="fas fa-calendar-check"></i>
                Reservation
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}">
                <i class="fas fa-shopping-cart"></i>
                Orders
            </a>
        </li>

        <li class="{{ request()->routeIs('menu.*') ? 'active' : '' }}">
            <a href="{{ route('menu.index') }}">
                <i class="fas fa-utensils"></i>
                Menu
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
            <a href="{{ route('admin.tables.index') }}">
                <i class="fas fa-chair"></i>
                Tables
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.franchise.*') ? 'active' : '' }}">
            <a href="{{ route('admin.franchise.index') }}">
                <i class="fas fa-store"></i>
                Franchise
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.story-review.*') ? 'active' : '' }}">
            <a href="{{ route('admin.story-review.index') }}">
                <i class="fas fa-star"></i>
                Stories & Reviews
            </a>
        </li>

        <li class="{{ request()->routeIs('team.*') ? 'active' : '' }}">
            <a href="{{ route('team.index') }}">
                <i class="fas fa-users"></i>
                Team
            </a>
        </li>

        <li class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <a href="{{ route('settings.index') }}">
                <i class="fas fa-cog"></i>
                Settings
            </a>
        </li>

    </ul>

    <!-- Logout -->
    <div class="nav-logout">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </div>

</nav>