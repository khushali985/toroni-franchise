<!DOCTYPE html>
<html>

<head>

    <title>Admin Forgot Password</title>

    <link rel="stylesheet" href="{{ asset('css/admin-auth.css') }}">

</head>

<body>

    <div class="admin-auth-container">

        <div class="admin-auth-card">

            <h2 class="admin-auth-title">Forgot Password</h2>

            <form method="POST" action="{{ route('admin.send.reset.link') }}">
                @csrf

                <input type="email" name="email" placeholder="Enter admin email" class="admin-input" required>

                <button type="submit" class="admin-btn">
                    Send Reset Link
                </button>

            </form>

        </div>

    </div>
    @if(session('success'))
    <div id="toast-success" class="toast">
        {{ session('success') }}
    </div>
    @endif
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            const toast = document.getElementById("toast-success");

            if (toast) {

                setTimeout(() => {
                    toast.classList.add("show");
                }, 100);

                setTimeout(() => {
                    toast.classList.remove("show");
                }, 3000);

            }

        });

    </script>
</body>

</html>