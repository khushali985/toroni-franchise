document.addEventListener("DOMContentLoaded", () => {

    const dataElement = document.getElementById("team-members-data");
    const teamMembers = JSON.parse(dataElement.textContent);

    if (!teamMembers || teamMembers.length === 0) {
        console.warn("No team members found");
        return;
    }

    let index = 0;
    let interval = null;

    const chefImg = document.getElementById("chefImg");
    const chefDesc = document.getElementById("chefDesc");
    const chefTitle = document.getElementById("chefTitle");
    const slider = document.querySelector(".chef-slider");

    // =========================
    // UPDATE SLIDE (SMOOTH)
    // =========================
    function updateChef() {
        const member = teamMembers[index];

        // remove animation classes
        chefImg.classList.remove("show");
        chefTitle.classList.remove("text-show");
        chefDesc.classList.remove("text-show");

        setTimeout(() => {
            chefImg.src = "/images/team/" + member.image;
            chefTitle.textContent = member.role;
            chefDesc.textContent = member.description;

            // add animation classes
            chefImg.classList.add("show");
            chefTitle.classList.add("text-show");
            chefDesc.classList.add("text-show");
        }, 300);
    }

    // =========================
    // AUTO SLIDE
    // =========================
    function startAutoSlide() {
        stopAutoSlide(); // prevent multiple intervals

        interval = setInterval(() => {
            index = (index + 1) % teamMembers.length;
            updateChef();
        }, 6000); // 6 sec
    }

    function stopAutoSlide() {
        if (interval) {
            clearInterval(interval);
            interval = null;
        }
    }

    // =========================
    // INITIAL LOAD
    // =========================
    updateChef();
    startAutoSlide();

    // =========================
    // BUTTON CONTROLS
    // =========================
    document.getElementById("next").addEventListener("click", () => {
        stopAutoSlide();
        index = (index + 1) % teamMembers.length;
        updateChef();
        startAutoSlide();
    });

    document.getElementById("prev").addEventListener("click", () => {
        stopAutoSlide();
        index = (index - 1 + teamMembers.length) % teamMembers.length;
        updateChef();
        startAutoSlide();
    });

    // =========================
    // HOVER PAUSE (FIXED)
    // =========================
    const section = document.querySelector(".about-section");

    if (section) {
        section.addEventListener("mouseenter", stopAutoSlide);
        section.addEventListener("mouseleave", startAutoSlide);
    }

    // =========================
    // TAB SWITCH PAUSE
    // =========================
    document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
            stopAutoSlide();
        } else {
            startAutoSlide();
        }
    });

});