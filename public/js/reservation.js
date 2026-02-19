document.addEventListener("DOMContentLoaded", function () {

    const steps = document.querySelectorAll(".form-step");
    const indicators = document.querySelectorAll(".step");
    const nextBtns = document.querySelectorAll(".next-btn");
    const prevBtns = document.querySelectorAll(".prev-btn");

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

            // Manual validation for time in Step 2
            if (current === 1) {
                if (!hiddenTimeInput.value) {
                    alert("Please select a time slot.");
                    return;
                }

                // ✅ Check availability via AJAX
                const franchiseId = document.querySelector("select[name='franchise_id']").value;
                const date = document.querySelector("input[name='date']").value;
                const noOfPeople = document.querySelector("select[name='no_of_people']").value;
                const time = hiddenTimeInput.value;

                if (!franchiseId || !date || !noOfPeople) {
                    alert("Please select location, date, and number of guests first.");
                    return;
                }

                try {
                    const response = await fetch(checkAvailabilityUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            franchise_id: franchiseId,
                            date: date,
                            time: hiddenTimeInput.value,
                            no_of_people: noOfPeople
                        })
                    });

                    if (!response.ok) {
                        throw new Error("Server error: " + response.status);
                    }

                    const data = await response.json();


                    if (!data.available) {
                        alert("No tables available for selected date, time, and guests. Please choose another.");
                        return;
                    }

                } catch (error) {
                    console.error(error);
                    alert("Error checking availability. Please try again.");
                    return;
                }
            }

            if (valid && current < steps.length - 1) {
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

    // DATE PICKER
    flatpickr("#date", {
        dateFormat: "Y-m-d",
        allowInput: true,
        minDate: "today"
    });

    // TIME SLOT SELECTION
    const slotButtons = document.querySelectorAll(".time-slot");
    const hiddenTimeInput = document.getElementById("selectedTime");

    slotButtons.forEach(btn => {
        btn.addEventListener("click", function () {

            slotButtons.forEach(b => b.classList.remove("active-slot"));

            this.classList.add("active-slot");

            hiddenTimeInput.value = this.dataset.time;
        });
    });

});
