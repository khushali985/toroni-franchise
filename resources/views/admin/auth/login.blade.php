@extends('layouts.app')

@section('title', 'Admin Login')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
@endpush

@section('content')

<section class="admin-login-section">

    <div class="login-container">
        <div class="login-box">

            <h1 class="title">Admin Login</h1>

            {{-- Session Error --}}
            @if(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
            <div class="alert error">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" id="adminLoginForm">
                @csrf

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Admin Email"
                        required>
                </div>

                <div class="input-group password-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter Password" required>

                    <span class="toggle-password" onclick="togglePassword()">👁</span>
                </div>

                <button type="submit" class="login-btn">
                    Login
                </button>

            </form>

        </div>
    </div>

</section>

@endsection


@push('scripts')
<script src="{{ asset('js/admin-login.js') }}"></script>
@endpush