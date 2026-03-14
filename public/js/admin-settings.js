document.addEventListener("DOMContentLoaded", function () {

    function hideAllForms() {

        document.getElementById("settingsForm").style.display = "none";
        document.getElementById("passwordForm").style.display = "none";
        document.getElementById("emailForm").style.display = "none";

    }

    window.toggleForm = function (id) {

        hideAllForms();

        document.getElementById(id).style.display = "block";

    }

    window.closeForms = function () {

        hideAllForms();

    }


    // logo preview
    const logoInput = document.getElementById("logoInput");
    const logoPreview = document.getElementById("logoPreview");

    if (logoInput) {

        logoInput.addEventListener("change", function (e) {

            const file = e.target.files[0];

            if (file) {

                const reader = new FileReader();

                reader.onload = function (event) {

                    logoPreview.src = event.target.result;
                    logoPreview.style.display = "block";

                }

                reader.readAsDataURL(file);

            }

        });

    }


    // success message auto hide
    const success = document.querySelector(".success-msg");

    if (success) {

        setTimeout(() => {
            success.style.display = "none"
        }, 3000)

    }

});