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


    <div class="settings-actions">

        <button class="action-btn" onclick="toggleForm('settingsForm')">
            Update Settings
        </button>

        <button class="action-btn" onclick="toggleForm('passwordForm')">
            Change Password
        </button>

        <button class="action-btn" onclick="toggleForm('emailForm')">
            Change Email
        </button>

    </div>


    {{-- UPDATE SETTINGS FORM --}}
    <div id="settingsForm" class="settings-card form-hidden">

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $settings->email ?? '' }}">
                </div>

                <div class="form-group">
                    <label>Contact</label>
                    <input type="text" name="contact" value="{{ $settings->contact ?? '' }}">
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
                <img id="logoPreview" src="{{ asset($settings->logo) }}" class="logo-preview" loading="lazy">
                @endif
            </div>

            <button class="save-btn">Save Settings</button>

            <button type="button" class="cancel-btn" onclick="closeForms()">Cancel</button>

        </form>

    </div>



    {{-- PASSWORD FORM --}}
    <div id="passwordForm" class="settings-card form-hidden">

        <h3>Change Password</h3>

        <form action="{{ route('admin.change.password') }}" method="POST">
            @csrf

            <input class="password" type="password" name="password" placeholder="New Password">

            <input class="password" type="password" name="password_confirmation" placeholder="Confirm Password">

            <button class="save-btn">Update Password</button>

            <button type="button" class="cancel-btn" onclick="closeForms()">Cancel</button>

        </form>

    </div>



    {{-- EMAIL FORM --}}
    <div id="emailForm" class="settings-card form-hidden">

        <h3>Change Email</h3>

        <form action="{{ route('admin.change.email') }}" method="POST">
            @csrf

            <input type="email" name="email" placeholder="Enter new email">

            <button class="save-btn">Update Email</button>

            <button type="button" class="cancel-btn" onclick="closeForms()">Cancel</button>

        </form>

    </div>



    {{-- SETTINGS TABLE --}}
    <div class="settings-table">

        <table>

            <tr>
                <th>Email</th>
                <td>{{ $settings->email ?? '-' }}</td>
            </tr>

            <tr>
                <th>Contact</th>
                <td>{{ $settings->contact ?? '-' }}</td>
            </tr>

            <tr>
                <th>Opening Hours</th>
                <td>{{ $settings->opening_hours ?? '-' }}</td>
            </tr>

            <tr>
                <th>Address</th>
                <td>{{ $settings->address ?? '-' }}</td>
            </tr>

            <tr>
                <th>Logo</th>
                <td>

                    @if(!empty($settings->logo))
                    <img src="{{ asset($settings->logo) }}" width="70" loading="lazy">
                    @endif

                </td>
            </tr>

        </table>

    </div>

</div>

@endsection


@push('scripts')
<script src="{{ asset('js/admin-settings.js') }}"></script>
@endpush