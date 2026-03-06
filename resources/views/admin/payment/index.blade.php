@extends('layouts.admin')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-payment.css') }}">
@endpush
<h2>Payment Management</h2>

@if(session('success'))
<div style="color:green;">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.payment.store') }}" enctype="multipart/form-data">
    @csrf

    <label>Select Franchise</label>
    <select name="franchise_id" required>
        @foreach($franchises as $franchise)
        <option value="{{ $franchise->id }}">
            {{ $franchise->location }}
        </option>
        @endforeach
    </select>

    <br><br>

    <label>UPI Name</label>
    <input type="text" name="upi_name">

    <br><br>

    <label>Upload QR</label>
    <input type="file" name="qr_image" required>

    <br><br>

    <button type="submit">Save QR</button>

</form>

<hr>

<h3>Existing QRs</h3>

@foreach($payments as $payment)
@if($payment->qr_image)
<div style="margin-bottom:20px;">
    <strong>{{ $payment->franchise->location }}</strong><br>
    <img src="{{ asset($payment->qr_image) }}" width="150">
    @if($payment->upi_name)
    <p>{{ $payment->upi_name }}</p>
    @endif
</div>
@endif
@endforeach

@endsection