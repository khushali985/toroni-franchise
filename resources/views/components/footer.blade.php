<!-- FOOTER -->

@php
$settings = \App\Models\Setting::first();
@endphp

<footer id="contact-section" class="footer">
    <div class="footer-container">

        <div class="footer-brand">

            @if(!empty($settings->logo))
            <img src="{{ asset($settings->logo) }}" alt="{{ $settings->restaurant_name }}" class="footer-logo">
            @else
            <img src="{{ asset('images/Logo.jpg') }}" alt="Logo" class="footer-logo">
            @endif

            <p>{{ $settings->restaurant_name ?? 'Toroni Italian Ristorante' }}</p>

        </div>


        <div class="footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('franchise') }}">Franchise</a></li>
                <li><a href="#contact-section">Contact Us</a></li>
            </ul>
        </div>


        <div class="footer-contact">
            <h3>Address</h3>
            <p>{{ $settings->address ?? 'Address not set' }}</p>

            <h3>Email</h3>
            <p>{{ $settings->email ?? 'Email not set' }}</p>
        </div>


        <div class="footer-hours">
            <h3>Opening Hours</h3>
            <p>{{ $settings->opening_hours ?? 'Not updated yet' }}</p>

            <h3>Contact</h3>
            <p>{{ $settings->contact ?? 'Not updated yet' }}</p>
        </div>

    </div>

    <div class="footer-bottom">
        <p>© {{ date('Y') }} {{ $settings->restaurant_name ?? 'Restaurant' }}. All Rights Reserved.</p>
    </div>
</footer>