<!DOCTYPE html>
<html>

<head>

    <title>Admin Reset Password</title>

    <link rel="stylesheet" href="{{ asset('css/admin-auth.css') }}">

</head>

<body>

    <div class="admin-auth-container">

        <div class="admin-auth-card">

            <h2 class="admin-auth-title">Reset Password</h2>

            {{-- ERROR MESSAGE --}}
            @if ($errors->any())
            <div class="success-message" style="background:#f8d7da;color:#721c24;">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.update.password') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <input type="email" name="email" placeholder="Email" class="admin-input" required>

                <input type="password" name="password" placeholder="New Password" class="admin-input" required>

                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="admin-input"
                    required>

                <button type="submit" class="admin-btn">
                    Update Password
                </button>

            </form>

        </div>

    </div>

</body>

</html>