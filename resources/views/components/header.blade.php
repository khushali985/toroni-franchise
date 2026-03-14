<button class="Explore" onclick="openMenu()">
    <h2 class="star-text"> ⭐ </h2>
</button>

<div class="menu-overlay" id="menu">
    <span class="close-btn" onclick="closeMenu()">✕</span>

    <div class="menu-content">
        <div class="menu-left">
            <p>Welcome to Our Restaurant</p>
            <img src="{{ asset('images/hearder_side_bg.jpg') }}" class="menu-image" loading="lazy">
            <a href="{{ route('home') }}" class="menu-logo-link">
                @if(!empty($settings->logo))
                <img src="{{ asset($settings->logo) }}" alt="{{ $settings->restaurant_name }}" class="menu-logo"
                    loading="lazy">
                @else
                <img src="{{ asset('images/Logo.jpg') }}" alt="Logo" class="menu-logo" loading="lazy">
                @endif
            </a>
        </div>

        <div class="menu-right">
            <a href="{{ route('about') }}" class="menu-component">ABOUT US</a>
            <a href="{{ route('franchise') }}" class="menu-component">FRANCHISE</a>
            <a href="#contact-section" class="menu-component" onclick="closeMenu()">CONTACT US</a>
        </div>
    </div>
</div>