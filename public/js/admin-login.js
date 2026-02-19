function togglePassword() {
    const passwordInput = document.getElementById("password");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}

// Optional: Simple Frontend Validation
document.getElementById("loginForm").addEventListener("submit", function (e) {
    const email = document.querySelector("input[name='email']").value;
    const password = document.querySelector("input[name='password']").value;

    if (email.trim() === "" || password.trim() === "") {
        alert("Please fill in all fields.");
        e.preventDefault();
    }
});
