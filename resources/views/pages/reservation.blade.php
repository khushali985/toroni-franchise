@extends('layouts.app')

@section('title', 'Reserve Your Table')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

        <section class="taj-booking-section">
            <div class="taj-booking-card">
                <p class="taj-subtitle">Reserve your dining experience</p>

                <form method="POST" action="{{ route('reservation.store') }}" enctype="multipart/form-data"
                    id="multiStepForm">
                    @csrf

                    <div class="step-indicator">
                        <div class="step active">1. Booking Details</div>
                        <div class="step">2. Guest Details</div>
                        <div class="step">3. Payment</div>
                    </div>

                    {{-- STEP 1 --}}
                    <div class="form-step active">

                        <label>Select Location</label>
                        <select name="franchise_id" required>
                            <option value="">Select Location</option>
                            @foreach($franchises as $franchise)
                            <option value="{{ $franchise->id }}" {{ old('franchise_id')==$franchise->id ? 'selected' :
                                '' }}>
                                {{ $franchise->location }}
                            </option>
                            @endforeach
                        </select>

                        <label>Date</label>
                        <input type="text" id="date" name="date" value="{{ old('date') }}" placeholder="YYYY-MM-DD"
                            required>

                        <label>No. of Guests</label>
                        <select name="no_of_people" required>
                            <option value="">Select</option>
                            @for($i=1;$i<=10;$i++) <option value="{{ $i }}" {{ old('no_of_people')==$i ? 'selected' :''
                                }}>
                                {{ $i }} Guests
                                </option>
                                @endfor
                        </select>

                        <button type="button" class="btn next-btn">Continue</button>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="form-step">
                        {{-- TIME SLOTS --}}
                        <label>Select Available Time</label>

                        <div class="time-slots">
                            <button type="button" class="time-slot" data-time="12:00">12:00 PM</button>
                            <button type="button" class="time-slot" data-time="12:30">12:30 PM</button>
                            <button type="button" class="time-slot" data-time="13:00">1:00 PM</button>
                            <button type="button" class="time-slot" data-time="13:30">1:30 PM</button>
                            <button type="button" class="time-slot" data-time="14:00">2:00 PM</button>
                            <button type="button" class="time-slot" data-time="14:30">2:30 PM</button>
                            <button type="button" class="time-slot" data-time="18:00">6:00 PM</button>
                            <button type="button" class="time-slot" data-time="18:30">6:30 PM</button>
                            <button type="button" class="time-slot" data-time="19:00">7:00 PM</button>
                            <button type="button" class="time-slot" data-time="19:30">7:30 PM</button>
                            <button type="button" class="time-slot" data-time="20:00">8:00 PM</button>
                            <button type="button" class="time-slot" data-time="20:30">8:30 PM</button>
                            <button type="button" class="time-slot" data-time="21:00">9:00 PM</button>
                            <button type="button" class="time-slot" data-time="21:30">9:30 PM</button>
                        </div>

                        <input type="hidden" name="time" id="selectedTime">

                        <label>Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Your Name"
                            required>

                        <label>Contact Number</label>
                        <input type="tel" name="phone_no" value="{{ old('phone_no') }}" placeholder="Your Phone Number"
                            required>

                        <button type="button" class="btn prev-btn">Back</button>
                        <button type="button" class="btn next-btn">Continue</button>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="form-step">

                        <div class="qr-section">
                            <div class="qr-box">
                                <img src="{{ asset('images/upi_qr.jpeg') }}">
                            </div>

                            <div class="qr-text">
                                <h4>Advance Payment Required</h4>
                                <p>
                                    Please scan the QR code to complete your booking.
                                    Reservation will be confirmed after verification.
                                </p>
                            </div>
                        </div>

                        <input type="text" name="transaction_id" placeholder="Enter UPI Transaction ID" required>

                        <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>

                        <button type="button" class="btn prev-btn">Back</button>
                        <button type="submit" class="btn">Confirm Reservation</button>
                    </div>

                </form>
            </div>
        </section>
    </div>
</section>
@endsection
@push('scripts')
<script>
    const checkAvailabilityUrl = "{{ route('reservation.checkAvailability') }}";
</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('js/reservation.js') }}"></script>

@endpush