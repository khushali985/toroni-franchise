document.addEventListener("DOMContentLoaded", () => {

    const dataElement = document.getElementById("team-members-data");
    const teamMembers = JSON.parse(dataElement.textContent);

    if (!teamMembers || teamMembers.length === 0) {
        console.warn("No team members found");
        return;
    }

    let index = 0;

    const chefImg = document.getElementById("chefImg");
    const chefDesc = document.getElementById("chefDesc");
    const chefTitle = document.getElementById("chefTitle");

    function updateChef() {

        const member = teamMembers[index];

        // IMAGE (matches admin upload folder)
        chefImg.src = "/images/team/" + member.image;

        // ROLE
        chefTitle.textContent = member.role;

        // DESCRIPTION
        chefDesc.textContent = member.description;
    }

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