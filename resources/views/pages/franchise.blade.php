@extends('layouts.app')

@section('title', 'Toronee Franchise')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/franchise.css') }}">
<link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500&display=swap"
    rel="stylesheet">
@endpush

@section('content')

<!-- FRANCHISE SECTION -->
<section class="franchise">
    <h1>FRANCHISE</h1>

    <div class="franchise-grid" id="franchiseGrid">
        <!-- Cards injected via JS -->
    </div>
</section>

<!-- PARTNER SECTION -->
<section class="partner">
    <div class="partner-content">

        <div class="partner-header">
            <h2>PARTNER WITH Toronee</h2>
            <p>Build a timeless Italian dining experience</p>
        </div>

        <form method="POST" action="{{ route('franchise.partner.store') }}">
            @csrf

            <input type="text" name="name" placeholder="Full Name" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="tel" name="phone" placeholder="Phone Number" />
            <input type="text" name="city" placeholder="City for Franchise" required />

            <button type="submit">Submit</button>
        </form>

        @if(session('success'))
        <div id="successPopup" class="popup-overlay">
            <div class="popup-box">
                <h3>Success 🎉</h3>
                <p>{{ session('success') }}</p>
                <button onclick="closePopup()">OK</button>
            </div>
        </div>
        @endif

    </div>
</section>

{{-- Blade → JSON binding (editor-safe) --}}
<script id="franchise-data" type="application/json">
        {!! json_encode($franchises) !!}
    </script>

@endsection

@push('scripts')
<script src="{{ asset('js/franchise.js') }}"></script>
@endpush