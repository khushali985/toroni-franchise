document.addEventListener("DOMContentLoaded", () => {

    const dataEl = document.getElementById("franchise-data");
    const franchises = JSON.parse(dataEl.textContent);

    const grid = document.getElementById("franchiseGrid");

    franchises.forEach(item => {
        const card = document.createElement("div");
        card.className = "card";

        card.innerHTML = `
            <div class="card-inner">
                <div class="card-front">
                    <img src="/storage/${item.image}" alt="${item.location}">
                </div>
                <div class="card-back">
                    <h3>${item.location}</h3>
                    <p>${item.address}</p>
                </div>
            </div>
        `;

        grid.appendChild(card);
    });

});
