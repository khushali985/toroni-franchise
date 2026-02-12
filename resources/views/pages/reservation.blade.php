@extends('layouts.app')

@section('title', 'Reserve Your Table')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
@endpush

@section('content')

<section class="main-component">

    <div class="reservation-box">
        <h1>Reserve Your Table</h1>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
        <div class="success-msg">
            {{ session('success') }}
        </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
        <div class="error-msg">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('reservation.store') }}">
            @csrf

            {{-- Name --}}
            <div class="full">
                <label>Name</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Your Name" required>
            </div>

            {{-- Phone --}}
            <div>
                <label>Contact</label>
                <input type="tel" name="phone_no" value="{{ old('phone_no') }}" placeholder="Your Phone Number"
                    required>
            </div>

            {{-- Franchise Location --}}
            <div class="full">
                <label>Select Location</label>
                <select name="franchise_id" required>
                    <option value="">Select Location</option>
                    @foreach($franchises as $franchise)
                    <option value="{{ $franchise->id }}" {{ old('franchise_id')==$franchise->id ? 'selected' : '' }}>
                        {{ $franchise->location }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Date --}}
            <div>
                <label>Date</label>
                <input type="date" name="date" value="{{ old('date') }}" required>
            </div>

            {{-- Time --}}
            <div>
                <label>Time</label>
                <input type="time" name="time" value="{{ old('time') }}" required>
            </div>

            {{-- Number of People --}}
            <div class="full">
                <label>No. of People</label>
                <select name="no_of_people" required>
                    <option value="">Select</option>
                    @for($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ old('no_of_people')==$i ? 'selected' : ''
                        }}>
                        {{ $i }}
                        </option>
                        @endfor
                </select>
            </div>

            {{-- Table Selection --}}
            <div class="full">
                <label>Select Table</label>
                <select name="table_id" required>
                    <option value="">Select Table</option>
                    @foreach($tables as $table)
                    <option value="{{ $table->id }}" {{ old('table_id')==$table->id ? 'selected' : '' }}>
                        Table {{ $table->table_no }}
                        (Capacity: {{ $table->capacity_people }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn">
                Confirm Reservation
            </button>

        </form>
    </div>

</section>

{{-- EXPERIENCE SECTION --}}
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
        <img src="{{ asset('images/reserve_side.png') }}" alt="Food">
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
<script src="{{ asset('js/reservation.js') }}"></script>
@endpush