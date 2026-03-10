document.addEventListener("DOMContentLoaded", function () {

    // Logo preview
    const logoInput = document.getElementById("logoInput");
    const logoPreview = document.getElementById("logoPreview");

    if (logoInput) {

        logoInput.addEventListener("change", function (e) {

            const file = e.target.files[0];

            if (file) {

                const reader = new FileReader();

                reader.onload = function (event) {
                    logoPreview.src = event.target.result;
                    logoPreview.classList.remove("hidden");
                }

                reader.readAsDataURL(file);

            }

        });

    }


    // auto hide success message
    const success = document.querySelector(".success-msg");

    if (success) {
        setTimeout(() => {
            success.style.display = "none";
        }, 3000);
    }

});