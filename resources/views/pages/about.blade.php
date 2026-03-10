@extends('layouts.app')

@section('title', 'About Us')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')

<section class="about-section">

    <h1 class="title">About Us</h1>

    <div class="about-content">
        <div class="about-img">
            <img src="{{ asset('images/about_side_bg.jpg') }}" alt="Restaurant">
        </div>

        <div class="about-text">
            <p>
                Rooted in the traditions of Italian cuisine, our story is shaped by a
                respect for craftsmanship and the belief that dining should feel meaningful.
            </p>

            <p>
                We draw inspiration from the way Italian meals bring people together —
                unhurried, thoughtful, and full of warmth.
            </p>

            <p>
                Over time, this vision became a place where carefully prepared food and
                attentive service come together, offering an experience that feels
                refined yet welcoming.
            </p>
        </div>
    </div>

    <!-- TEAM / CHEF SECTION -->
    <h2 class="subtitle" id="chefTitle"></h2>

    <div class="chef-slider">
        <button id="prev">&#10094;</button>

        <!-- Image will be set by JS -->
        <img id="chefImg" src="" alt="Team Member">

        <button id="next">&#10095;</button>
    </div>

    <!-- Description will be set by JS -->
    <p class="chef-desc" id="chefDesc"></p>

</section>

<!-- Blade JSON payload -->
<script id="team-members-data" type="application/json">
@json($teamMembers)
</script>


@endsection

@push('scripts')
<script src="{{ asset('js/about.js') }}"></script>
@endpush