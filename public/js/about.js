document.addEventListener("DOMContentLoaded", () => {

    const teamMembers = window.teamMembers || [];

    if (teamMembers.length === 0) {
        console.warn("No team members found");
        return;
    }

    let index = 0;

    const chefImg = document.getElementById("chefImg");
    const chefDesc = document.getElementById("chefDesc");
    const chefTitle = document.getElementById("chefTitle");

    function updateChef() {
        const member = teamMembers[index];

        chefImg.src = `/storage/${member.image}`;
        chefDesc.textContent = member.description;
        chefTitle.textContent = member.title;
    }

    // Initial render
    updateChef();

    document.getElementById("next").addEventListener("click", () => {
        index = (index + 1) % teamMembers.length;
        updateChef();
    });

    document.getElementById("prev").addEventListener("click", () => {
        index = (index - 1 + teamMembers.length) % teamMembers.length;
        updateChef();
    });

});
