@extends('layouts.admin')

@section('title','Settings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-settings.css') }}">
@endpush

@section('content')

<div class="settings-container">

    <h2>Restaurant Settings</h2>

    @if(session('success'))
    <p class="success-msg">{{ session('success') }}</p>
    @endif

    <div class="settings-card">

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">

                <!--<div class="form-group">
                    <label>Restaurant Name</label>
                    <input type="text" name="restaurant_name" value="{{ $settings->restaurant_name ?? '' }}">
                </div>-->

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $settings->email ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact" value="{{ $settings->contact ?? '' }}" pattern="[0-9]{10}"
                        maxlength="10">
                </div>

                <div class="form-group">
                    <label>Opening Hours</label>
                    <input type="text" name="opening_hours" value="{{ $settings->opening_hours ?? '' }}">
                </div>

            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address">{{ $settings->address ?? '' }}</textarea>
            </div>

            <div class="form-group">
                <label>Logo</label>
                <input type="file" name="logo" id="logoInput">

                @if(!empty($settings->logo))
                <img id="logoPreview" src="{{ asset($settings->logo) }}" class="logo-preview">
                @else
                <img id="logoPreview" class="logo-preview hidden">
                @endif
            </div>

            <button type="submit" class="save-btn">Update Settings</button>

        </form>

    </div>


    <div class="settings-card">

        <h3>Change Admin Password</h3>

        <form action="{{ route('admin.change.password') }}" method="POST">
            @csrf

            <div class="form-group">
                <input type="password" name="password" placeholder="New Password">

                @error('password')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <input type="password" name="password_confirmation" placeholder="Confirm Password">

                @error('password_confirmation')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <button class="save-btn">Change Password</button>

        </form>

    </div>


    <div class="settings-card">

        <h3>Change Admin Email</h3>

        <form action="{{ route('admin.change.email') }}" method="POST">
            @csrf

            <input type="email" name="email" placeholder="Enter new admin email">
            @error('email')
            <span class="error-text">{{ $message }}</span>
            @enderror

            <button class="save-btn">Change Email</button>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script src="{{ asset('js/admin-settings.js') }}"></script>
@endpush