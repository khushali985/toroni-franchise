@extends('layouts.app')

@section('title', 'Home | Toroni Restaurant')

@section('styles')

@endsection

@section('content')

<!-- ================= HERO SECTION ================= -->
<div class="hero">
    <img src="{{ asset('images/home_hero.png') }}" alt="Hero Image" class="hero-image" />
    <img src="{{ asset('images/Logo.jpg') }}" alt="Logo" class="hero-logo" />

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
            <img src="{{ asset('storage/'.$story->story_img) }}" alt="Story Image">
        </div>
        @empty
        <p style="padding:20px;">No stories available.</p>
        @endforelse

        <!-- duplicate stories for smooth infinite scroll -->
        @foreach($stories as $story)
        <div class="story-card">
            <img src="{{ asset('storage/'.$story->story_img) }}" alt="Story Image">
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
            ⭐⭐⭐⭐⭐
            <p>{{ $review->review_text }}</p>
            <span>- {{ $review->cust_name }}</span>
        </div>
        @empty
        <p>No reviews yet.</p>
        @endforelse

    </div>
</div>

@endsection