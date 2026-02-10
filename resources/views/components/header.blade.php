<button class="Explore" onclick="openMenu()">
    <h2 class="star-text"> ⭐ </h2>
    <h2 class="explore-text"> Menu </h2>
</button>

<div class="menu-overlay" id="menu">
    <span class="close-btn" onclick="closeMenu()">✕</span>

    <div class="menu-content">
        <div class="menu-left">
            <p>Welcome to Our Restaurant</p>
            <img src="{{ asset('images/header_side_bg.jpg') }}" class="menu-image">
            <img src="{{ asset('images/Logo.jpg') }}" class="menu-logo">
        </div>

        <div class="menu-right">
            <a href="{{ route('about') }}" class="menu-item">ABOUT US</a>
            <a href="{{ route('franchise') }}" class="menu-item">FRANCHISE</a>
            <a href="#" class="menu-item">CONTACT US</a>
        </div>
    </div>
</div>