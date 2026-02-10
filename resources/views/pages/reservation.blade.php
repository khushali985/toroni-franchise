@extends('layouts.app')

@section('title', 'Reserve Your Table')

@push('styles')
<!-- Google Fonts -->
<link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500&display=swap"
    rel="stylesheet">

<!-- Page CSS -->
<link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
@endpush

@section('content')

<section class="hero">

    <div class="reservation-box">
        <h1>Reserve Your Table</h1>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
        <p class="success-msg">{{ session('success') }}</p>
        @endif

        <form id="reservationForm" method="POST" action="{{ route('reservation.store') }}">
            @csrf

            <div class="full">
                <label>Name</label>
                <input type="text" name="full_name" required>
            </div>

            <div>
                <label>Contact</label>
                <input type="tel" name="phone_no" required>
            </div>

            <div>
                <label>Location</label>
                <input type="text" name="location" required>
            </div>

            <div>
                <label>Date</label>
                <input type="date" name="date" required>
            </div>

            <div>
                <label>Time</label>
                <input type="time" name="time" required>
            </div>

            <div class="full">
                <label>No. of People</label>
                <select name="no_of_people" required>
                    <option value="">Select</option>
                    @for($i = 1; $i <= 6; $i++) <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                </select>
            </div>

            {{-- Hidden backend-required fields --}}
            <input type="hidden" name="franchise_id" value="1">
            <input type="hidden" name="table_id" value="1">

            <button type="submit" class="btn">
                Confirm Reservation
            </button>
        </form>
    </div>

</section>

<!-- EXPERIENCE SECTION -->
<section class="experience">
    <div class="experience-text">
        <h1>An Experience Worth Choosing</h1>
        <p>
            Every dish we serve and every detail you notice is guided by one belief —
            that your dining experience should feel complete and considered.
        </p>
        <p>
            From carefully prepared food to thoughtful service, we aim to create
            moments that feel effortless and refined.
        </p>
        <p>
            It’s our way of ensuring that every lunch or dinner with us feels worthy
            of your time, your trust, and your choice.
        </p>
    </div>

    <div class="experience-img">
        <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092" alt="Food">
    </div>
</section>

<div class="call">
    <a href="tel:+917507895000" class="call-btn">
        <p>
            Call us for any order or reservation related queries:<br>
            <b>+91 7507895000</b>
        </p>
    </a>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/ryt.js') }}"></script>
@endpush