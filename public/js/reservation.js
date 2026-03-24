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

    // 🔥 NEXT BUTTON
    nextBtns.forEach(btn => {
        btn.addEventListener("click", async function () {

            console.log("Button clicked", current);

            const inputs = steps[current].querySelectorAll("input:not([type='hidden']), select");
            let valid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    valid = false;
                }
            });

            if (!valid) return;

            // ✅ STEP 1 → STEP 2 (LOAD AVAILABLE SLOTS)
            if (current === 0) {

                const franchiseId = document.querySelector("select[name='franchise_id']").value;
                const date = document.querySelector("input[name='date']").value;
                const noOfPeople = document.querySelector("select[name='no_of_people']").value;

                if (!franchiseId || !date || !noOfPeople) {
                    alert("Please complete booking details first.");
                    return;
                }

                // 👉 Move to Step 2 immediately (better UX)
                showStep(1);

                const slotButtons = document.querySelectorAll(".time-slot");

                try {

                    await Promise.all([...slotButtons].map(async (btn) => {

                        let time = btn.dataset.time;

                        /*const response = await fetch(checkAvailabilityUrl, {
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
                                time: time,
                                no_of_people: noOfPeople
                            })
                        });*/

                        const response = await fetch(checkAvailabilityUrl, {
                            method: "POST",
                            credentials: "same-origin", // 🔥 THIS FIXES 419
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content")
                            },
                            body: JSON.stringify({
                                franchise_id: franchiseId,
                                date: date,
                                time: time,
                                no_of_people: noOfPeople
                            })
                        });

                        const data = await response.json();

                        if (data.success && data.available) {
                            btn.style.display = "inline-block";
                            btn.disabled = false;
                        } else {
                            btn.style.display = "none";
                        }

                    }));

                } catch (error) {
                    console.error(error);
                    alert("Error checking availability");
                }

                return; // ❗ VERY IMPORTANT (avoid double step change)
            }

            // ✅ STEP 2 VALIDATION (time must be selected)
            if (current === 1) {

                if (!hiddenTimeInput.value) {
                    alert("Please select a time slot.");
                    return;
                }
            }

            // 👉 Move forward normally
            if (current < steps.length - 1) {
                showStep(current + 1);
            }
        });
    });

    // 🔙 PREVIOUS BUTTON
    prevBtns.forEach(btn => {
        btn.addEventListener("click", function () {
            if (current > 0) {
                showStep(current - 1);
            }
        });
    });

    // 📅 DATE PICKER
    flatpickr("#date", {
        dateFormat: "Y-m-d",
        allowInput: true,
        minDate: "today"
    });

    // ⏰ TIME SLOT SELECT
    const slotButtons = document.querySelectorAll(".time-slot");

    slotButtons.forEach(btn => {
        btn.addEventListener("click", function () {

            slotButtons.forEach(b => b.classList.remove("active-slot"));

            this.classList.add("active-slot");
            hiddenTimeInput.value = this.dataset.time;
        });
    });

    // 💰 QR LOAD ON LOCATION CHANGE
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
                            <p>${data.upi_name ?? ''}</p>
                        `;
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