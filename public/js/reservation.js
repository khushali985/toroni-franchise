document.addEventListener("DOMContentLoaded", function () {

    const steps = document.querySelectorAll(".form-step");
    const indicators = document.querySelectorAll(".step");
    const nextBtns = document.querySelectorAll(".next-btn");
    const prevBtns = document.querySelectorAll(".prev-btn");
    const hiddenTimeInput = document.getElementById("selectedTime");
    const franchiseSelect = document.querySelector("select[name='franchise_id']");
    const qrBox = document.getElementById("qrBox");

    let current = 0;

    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.remove("active");
            indicators[i].classList.remove("active");
        });

        steps[index].classList.add("active");
        indicators[index].classList.add("active");

        current = index;
    }

    nextBtns.forEach(btn => {
        btn.addEventListener("click", async function () {

            const inputs = steps[current].querySelectorAll("input:not([type='hidden']), select");
            let valid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    valid = false;
                }
            });

            if (!valid) return;

            // Step 2 availability check
            if (current === 1) {

                if (!hiddenTimeInput.value) {
                    alert("Please select a time slot.");
                    return;
                }

                const franchiseId = document.querySelector("select[name='franchise_id']").value;
                const date = document.querySelector("input[name='date']").value;
                const noOfPeople = document.querySelector("select[name='no_of_people']").value;

                if (!franchiseId || !date || !noOfPeople) {
                    alert("Please complete booking details first.");
                    return;
                }

                try {

                    const response = await fetch(checkAvailabilityUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content")
                        },
                        body: JSON.stringify({
                            franchise_id: franchiseId,
                            date: date,
                            time: hiddenTimeInput.value,
                            no_of_people: noOfPeople
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        console.log(errorData);
                        alert("Validation failed. Please check inputs.");
                        return;
                    }

                    const data = await response.json();

                    if (!data.success) {
                        alert("Error checking availability.");
                        return;
                    }

                    if (!data.available) {
                        alert("No tables available for selected date and time.");
                        return;
                    }

                } catch (error) {
                    console.error(error);
                    alert("Network error. Please try again.");
                    return;
                }
            }

            if (current < steps.length - 1) {
                showStep(current + 1);
            }
        });
    });

    prevBtns.forEach(btn => {
        btn.addEventListener("click", function () {
            if (current > 0) {
                showStep(current - 1);
            }
        });
    });

    // Date picker
    flatpickr("#date", {
        dateFormat: "Y-m-d",
        allowInput: true,
        minDate: "today"
    });

    // Time slot selection
    const slotButtons = document.querySelectorAll(".time-slot");

    slotButtons.forEach(btn => {
        btn.addEventListener("click", function () {

            slotButtons.forEach(b => b.classList.remove("active-slot"));

            this.classList.add("active-slot");
            hiddenTimeInput.value = this.dataset.time;
        });
    });

    if (franchiseSelect) {
        franchiseSelect.addEventListener("change", function () {

            let franchiseId = this.value;

            if (!franchiseId) {
                qrBox.innerHTML = "<p>Please select a location first.</p>";
                return;
            }

            fetch("/get-payment/" + franchiseId)
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        qrBox.innerHTML = `
                        <img src="${data.qr_image}" width="200">
                        <p>${data.upi_name ?? ''}</p>`;
                    } else {
                        qrBox.innerHTML = "<p>No payment QR available for this location.</p>";
                    }

                })
                .catch(error => {
                    console.error(error);
                    qrBox.innerHTML = "<p>Error loading QR. Try again.</p>";
                });
        });
    }

});
