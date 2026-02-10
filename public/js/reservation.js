document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("reservationForm");

    if (!form) return;

    form.addEventListener("submit", function () {
        // Basic UX confirmation (does NOT stop form submit)
        alert(
            "Thank you!\n\n" +
            "Your reservation request has been submitted.\n" +
            "Our team will confirm it shortly."
        );
    });
});
