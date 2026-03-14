@extends('layouts.app')

@section('title', 'Home | Toroni Restaurant')

@section('styles')

@endsection

@php
$settings = \App\Models\Setting::first();
@endphp

@section('content')

<!-- ================= HERO SECTION ================= -->
<div class="hero">
    <img src="{{ asset('images/home_hero.png') }}" alt="Hero Image" class="hero-image" loading="lazy" />
    @if(!empty($settings->logo))
    <img src="{{ asset($settings->logo) }}" alt="{{ $settings->restaurant_name }}" class="hero-logo" loading="lazy">
    @else
    <img src="{{ asset('images/Logo.jpg') }}" alt="Logo" class="hero-logo" loading="lazy">
    @endif

    <h1>Toroni Italian Ristorante</h1>

    <div class="hero-actions">
        <a href="{{ route('order') }}" class="hero-btn">Order Now</a>
        <a href="{{ route('reservation') }}" class="hero-btn">Reserve Table</a>
    </div>
</div>

<!-- ================= STORIES TITLE ================= -->
<div class="stories-title">
    <h3>Stories</h3>
</div>

<!-- ================= STORIES SECTION ================= -->
<div class="stories">
    <div class="stories-track">

        @forelse($stories as $story)
        <div class="story-card">
            <img src="{{ asset($story->story_img) }}" alt="Story Image" loading="lazy">
        </div>
        @empty
        <p style="padding:20px;">No stories available.</p>
        @endforelse

        <!-- duplicate stories for smooth infinite scroll -->
        @foreach($stories as $story)
        <div class="story-card">
            <img src="{{ asset($story->story_img) }}" alt="Story Image" loading="lazy">
        </div>
        @endforeach

    </div>
</div>

<!-- ================= TESTIMONIALS ================= -->
<div class="testimonials">
    <h2 class="test-title">What Our Customers Say?</h2>

    <div class="testimonial-slider">

        @forelse($reviews as $review)

        <div class="testimonial-card">

            <div class="rating-stars">
                @for($i = 1; $i <= 5; $i++) @if($i <=$review->rating)
                    ⭐
                    @else
                    ☆
                    @endif
                    @endfor
            </div>

            <p>{{ $review->review_text }}</p>
            <span>- {{ $review->cust_name }}</span>

        </div>

        @empty
        <p>No reviews yet.</p>
        @endforelse

    </div>
</div>

@endsection