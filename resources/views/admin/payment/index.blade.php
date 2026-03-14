@extends('layouts.admin')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-payment.css') }}">
@endpush

<div class="payment-page">

    <h2 class="page-title">Payment Management</h2>

    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif


    <!-- Add Payment QR Form -->

    <div class="payment-card">

        <h3 class="card-title">Add Franchise QR</h3>

        <form method="POST" action="{{ route('admin.payment.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Select Franchise</label>
                <select name="franchise_id" required>
                    <option value="">Choose Franchise</option>
                    @foreach($franchises as $franchise)
                    <option value="{{ $franchise->id }}">
                        {{ $franchise->location }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>UPI Name</label>
                <input type="text" name="upi_name" placeholder="Enter UPI Name">
            </div>

            <div class="form-group">
                <label>Upload QR</label>
                <input type="file" name="qr_image" required>
            </div>

            <button type="submit" class="btn-primary">
                Save QR
            </button>

        </form>

    </div>


    <!-- Divider -->

    <hr class="section-divider">


    <!-- Existing QRs -->

    <div class="existing-qr">

        <h3 class="section-title">Existing Franchise QRs</h3>

        <div class="qr-grid">

            @forelse($payments as $payment)

            @if($payment->qr_image)

            <div class="qr-card">

                <div class="qr-header">
                    <strong>{{ $payment->franchise->location }}</strong>
                </div>

                <div class="qr-image">
                    <img src="{{ asset($payment->qr_image) }}" alt="QR Code" loading="lazy">
                </div>

                @if($payment->upi_name)
                <p class="upi-name">
                    {{ $payment->upi_name }}
                </p>
                @endif

            </div>

            @endif

            @empty

            <p class="no-data">No QR codes uploaded yet.</p>

            @endforelse

        </div>

    </div>

</div>

@endsection