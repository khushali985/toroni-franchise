const cart = document.getElementById("cart");
const totalEl = document.getElementById("total");
const searchInput = document.getElementById("searchInput");
const orderNowBtn = document.getElementById("orderNowBtn");
const confirmBtn = document.getElementById("confirmBtn");
const toast = document.getElementById("toast");

let items = {};

/* ---------- TOAST ---------- */
function showToast(msg) {
    toast.innerText = msg;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 2000);
}

/* ---------- CART ---------- */
function addToCart(name, price, btn) {
    if (window.event) window.event.stopPropagation();

    items[name]
        ? items[name].qty++
        : items[name] = {
            name,
            price,
            qty: 1,
            img: btn.parentElement.querySelector("img").src
        };

    updateCart();
    showToast(`${name} added`);
}

function updateCart() {
    cart.innerHTML = "";
    let total = 0;

    Object.values(items).forEach(item => {
        total += item.price * item.qty;

        cart.innerHTML += `
            <div class="cart-item">
                <img src="${item.img}">
                <h4>${item.name}</h4>
                <div class="qty">
                    <button onclick="changeQty('${item.name}', -1)">−</button>
                    <span>${item.qty}</span>
                    <button onclick="changeQty('${item.name}', 1)">+</button>
                </div>
                <button class="remove" onclick="removeItem('${item.name}')">Remove</button>
            </div>
        `;
    });

    totalEl.innerText = total;
    confirmBtn.disabled = Object.keys(items).length === 0;
}

function changeQty(name, delta) {
    items[name].qty += delta;
    if (items[name].qty <= 0) delete items[name];
    updateCart();
}

function removeItem(name) {
    delete items[name];
    updateCart();
}

/* ---------- SEARCH ---------- */
searchInput.addEventListener("input", () => {
    let value = searchInput.value.toLowerCase().trim();

    if (value.endsWith("s")) value = value.slice(0, -1);

    document.querySelectorAll(".menu-item").forEach(item => {
        const name = item.dataset.name.toLowerCase();
        const category = item.dataset.category.toLowerCase();
        const dishName = item.querySelector(".dish-name");

        dishName.innerHTML = item.dataset.name;

        if (!value || name.includes(value) || category.includes(value)) {
            item.style.display = "block";

            if (value && name.includes(value)) {
                const regex = new RegExp(`(${value})`, "gi");
                dishName.innerHTML = item.dataset.name.replace(
                    regex,
                    `<span class="highlight">$1</span>`
                );
            }
        } else {
            item.style.display = "none";
        }
    });
});

/* ---------- FILTER ---------- */
function filterCategory(category, btn) {
    document.querySelectorAll(".filter").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");

    document.querySelectorAll(".menu-item").forEach(item => {
        item.style.display =
            category === "all" || item.dataset.category === category
                ? "block"
                : "none";
    });
}

/* ---------- KEYBOARD ---------- */
document.addEventListener("keydown", e => {
    if (e.key === "/") {
        e.preventDefault();
        searchInput.focus();
    }
    if (e.key === "Escape") {
        searchInput.value = "";
        searchInput.dispatchEvent(new Event("input"));
    }
});

/* ---------- SCROLL ---------- */
orderNowBtn.onclick = () => {
    document.querySelector(".order-panel")
        .scrollIntoView({ behavior: "smooth" });
};

/* ---------- CONFIRM ---------- */
confirmBtn.onclick = () => {
    const name = document.getElementById("nameInput").value.trim();
    const phone = document.getElementById("phoneInput").value.trim();
    const address = document.getElementById("addressInput").value.trim();

    if (!Object.keys(items).length) {
        showToast("Your cart is empty");
        return;
    }

    if (!name || !address || !/^[0-9]{10}$/.test(phone)) {
        showToast("Please fill valid details");
        return;
    }

    showToast("Order placed successfully!");

    items = {};
    updateCart();

    document.getElementById("nameInput").value = "";
    document.getElementById("phoneInput").value = "";
    document.getElementById("addressInput").value = "";
};
