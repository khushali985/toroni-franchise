document.addEventListener("DOMContentLoaded", function () {

    const modal = document.getElementById("reservationModal");
    const closeBtn = document.getElementById("closeModal");

    document.querySelectorAll(".view-btn").forEach(button => {
        button.addEventListener("click", function () {

            document.getElementById("modalName").innerText = this.dataset.name;
            document.getElementById("modalPhone").innerText = this.dataset.phone;
            document.getElementById("modalDate").innerText = this.dataset.date;
            document.getElementById("modalTime").innerText = this.dataset.time;
            document.getElementById("modalno_of_people").innerText = this.dataset.no_of_people;

            modal.style.display = "flex";
        });
    });

    const openFormBtn = document.getElementById("openReservationForm");
    const formModal = document.getElementById("reservationFormModal");
    const closeFormBtn = document.getElementById("closeReservationForm");

    if (openFormBtn) {
        openFormBtn.addEventListener("click", function () {
            formModal.style.display = "flex";
        });
    }

    if (closeFormBtn) {
        closeFormBtn.addEventListener("click", function () {
            formModal.style.display = "none";
        });
    }


    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

});
