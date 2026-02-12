<button class="Explore" onclick="openMenu()">
    <h2 class="star-text"> ⭐ </h2>
    <h2 class="explore-text"> Menu </h2>
</button>

<div class="menu-overlay" id="menu">
    <span class="close-btn" onclick="closeMenu()">✕</span>

    <div class="menu-content">
        <div class="menu-left">
            <p>Welcome to Our Restaurant</p>
            <img src="{{ asset('images/hearder_side_bg.jpg') }}" class="menu-image">
            <a href="{{ route('home') }}" class="menu-logo-link">
                <img src="{{ asset('images/Logo.jpg') }}" class="menu-logo">
            </a>
        </div>

        <div class="menu-right">
            <a href="{{ route('about') }}" class="menu-component">ABOUT US</a>
            <a href="{{ route('franchise') }}" class="menu-component">FRANCHISE</a>
            <a href="#contact-section" class="menu-component" onclick="closeMenu()">CONTACT US</a>
        </div>
    </div>
</div>