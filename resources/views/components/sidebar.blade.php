<div class="sidebar">

    <!-- Logo / Title -->
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>

    <!-- Navigation -->
    <ul class="sidebar-menu">

        <li class="{{ request()->routeIs('reservations.*') ? 'active' : '' }}">
            <a href="{{ route('reservations.index') }}">
                <i class="fas fa-calendar-check"></i>
                <span>Reservation Management</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Order Management</span>
            </a>
        </li>



        <li class="{{ request()->routeIs('menu.*') ? 'active' : '' }}">
            <a href="{{ route('menu.index') }}">
                <i class="fas fa-utensils"></i>
                <span>Menu Management</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('payment.*') ? 'active' : '' }}">
            <a href="{{ route('admin.payment.index') }}">
                <i class="fas fa-credit-card"></i>
                <span>Payment Management</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('tables.*') ? 'active' : '' }}">
            <a href="{{ route('admin.tables.index') }}">
                <i class="fas fa-chair"></i>
                <span>Table Management</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('franchise.*') ? 'active' : '' }}">
            <a href="{{ route('admin.franchise.index') }}">
                <i class="fas fa-calendar-check"></i>
                <span>Franchise Management</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.stories-reviews.*') ? 'active' : '' }}">
            <a href="{{ route('admin.story-review.index') }}">
                <i class="fas fa-photo-video"></i>
                <span>Stories & Reviews</span>
            </a>
        </li>


        <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <a href="{{ route('reports.index') }}">
                <i class="fas fa-chart-line"></i>
                <span>Reports & Analytics</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <a href="{{ route('settings.index') }}">
                <i class="fas fa-cog"></i>
                <span>Settings Panel</span>
            </a>
        </li>

    </ul>

</div>