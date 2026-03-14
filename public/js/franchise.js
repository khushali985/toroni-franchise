document.addEventListener("DOMContentLoaded", () => {

    const dataEl = document.getElementById("franchise-data");
    const franchises = JSON.parse(dataEl.textContent);

    const grid = document.getElementById("franchiseGrid");

    franchises.forEach(item => {

        const card = document.createElement("div");
        card.className = "card";

        let imagePath = item.image
            ? "/" + item.image
            : "/images/default.png"; // optional fallback

        card.innerHTML = `
            <div class="card-inner">
                <div class="card-front">
                    <img src="${imagePath}" alt="${item.location}">
                </div>
                <div class="card-back">
                    <h3>${item.location}</h3>
                    <p>${item.owner_name}</p>
                    <p>${item.owner_email}</p>
                </div>
            </div>
        `;

        grid.appendChild(card);
    });

});

function closePopup() {
    document.getElementById('successPopup').style.display = 'none';
}