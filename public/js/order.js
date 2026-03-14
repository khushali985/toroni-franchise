/* ===============================
   ELEMENTS
================================= */

const cart = document.getElementById("cart");
const totalEl = document.getElementById("total");
const confirmBtn = document.getElementById("confirmBtn");
const toast = document.getElementById("toast");
const emptyMsg = document.getElementById("emptyCartMsg");

const franchiseScreen = document.getElementById("franchiseScreen");
const menuContent = document.getElementById("menuContent");
const selectedFranchiseInput = document.getElementById("selectedFranchise");
const selectedFranchiseTitle = document.getElementById("selectedFranchiseTitle");
const searchInput = document.getElementById("searchInput");

let items = {};
let activeCategory = "all";
let lockedFranchise = null;


/* ===============================
   TOAST
================================= */

function showToast(msg) {
    toast.innerText = msg;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 2000);
}

if (emptyMsg) {
    emptyMsg.remove();
}

if (cart.length === 0) {
    document.getElementById("cart").innerHTML =
        '<p id="emptyCartMsg" class="empty-cart">Your cart is empty</p>';
}

/* ===============================
   FRANCHISE SELECTION
================================= */

function selectFranchise(id, name) {

    // If cart has items and trying to change franchise
    if (lockedFranchise && lockedFranchise !== id) {
        showToast("You already added items from another franchise. Clear cart first.");
        return;
    }

    lockedFranchise = id;
    selectedFranchiseInput.value = id;

    franchiseScreen.style.display = "none";
    menuContent.style.display = "block";

    selectedFranchiseTitle.innerText = name + " Menu";

    updateCategoryVisibility();
    filterMenu();
    validateForm();
}

function changeFranchise() {

    if (Object.keys(items).length > 0) {
        showToast("Clear cart before changing franchise");
        return;
    }

    lockedFranchise = null;
    selectedFranchiseInput.value = "";

    franchiseScreen.style.display = "block";
    menuContent.style.display = "none";

    validateForm();
}


/* ===============================
   CART
================================= */

function addToCart(name, price, btn) {

    const currentFranchise = selectedFranchiseInput.value;

    // If no franchise selected
    if (!currentFranchise) {
        showToast("Please select a franchise first");
        return;
    }

    // Lock franchise if first item
    if (!lockedFranchise) {
        lockedFranchise = currentFranchise;
    }

    // If different franchise (extra safety)
    if (lockedFranchise !== currentFranchise) {
        showToast("You cannot mix items from different franchises");
        return;
    }

    items[name]
        ? items[name].qty++
        : items[name] = {
            name,
            price: parseFloat(price),
            qty: 1,
            img: btn.parentElement.querySelector("img").src
        };

    updateCart();
    showToast(name + " added");
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
                <button class="remove" onclick="removeItem('${item.name}')">
                    Remove
                </button>
            </div>
        `;
    });

    totalEl.innerText = total;
    validateForm();
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


/* ===============================
   FILTER LOGIC (SEARCH + CATEGORY + FRANCHISE)
================================= */

function filterMenu() {

    const franchise = selectedFranchiseInput.value;
    const searchValue = searchInput.value.toLowerCase().trim();

    document.querySelectorAll(".menu-item").forEach(item => {

        const itemName = item.dataset.name.toLowerCase();
        const itemCategory = item.dataset.category.toLowerCase();
        const itemFranchise = item.dataset.franchise;

        const matchFranchise = itemFranchise === franchise;
        const matchCategory = activeCategory === "all" || itemCategory === activeCategory;
        const matchSearch =
            !searchValue ||
            itemName.includes(searchValue) ||
            itemCategory.includes(searchValue);

        if (matchFranchise && matchCategory && matchSearch) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
}


/* ===============================
   SEARCH
================================= */

if (searchInput) {
    searchInput.addEventListener("input", filterMenu);
}


/* ===============================
   CATEGORY FILTER
================================= */

function filterCategory(category, btn) {

    activeCategory = category.toLowerCase();

    document.querySelectorAll(".filter").forEach(b =>
        b.classList.remove("active")
    );

    btn.classList.add("active");

    filterMenu();
}


/* ===============================
   FORM VALIDATION
================================= */

function validateForm() {

    const name = document.getElementById("nameInput").value.trim();
    const email = document.getElementById("emailInput").value.trim();
    const phone = document.getElementById("phoneInput").value.trim();
    const address = document.getElementById("addressInput").value.trim();
    const franchise = selectedFranchiseInput.value;

    const valid =
        Object.keys(items).length > 0 &&
        name.length > 0 &&
        address.length > 0 &&
        franchise.length > 0 &&
        /^[0-9]{10}$/.test(phone) &&
        /^\S+@\S+\.\S+$/.test(email);

    confirmBtn.disabled = !valid;
}


/* ===============================
   INPUT LISTENERS
================================= */

document.querySelectorAll(
    "#nameInput, #emailInput, #phoneInput, #addressInput"
).forEach(input => {
    input.addEventListener("input", validateForm);
});


/* ===============================
   FORM SUBMIT
================================= */

document.querySelector("form").addEventListener("submit", function (e) {

    const franchise = selectedFranchiseInput.value;

    if (!Object.keys(items).length) {
        e.preventDefault();
        showToast("Your cart is empty");
        return;
    }

    if (!franchise) {
        e.preventDefault();
        showToast("Please select a franchise");
        return;
    }

    document.getElementById("itemsInput").value =
        JSON.stringify(items);

    document.getElementById("totalInput").value =
        totalEl.innerText;
});


/* ===============================
   INITIAL LOAD
================================= */

document.addEventListener("DOMContentLoaded", () => {
    validateForm();
});


/* ===============================
   SUCCESS MODAL
================================= */

function closeSuccess() {
    window.location.reload();
}

function updateCategoryVisibility() {

    const franchise = selectedFranchiseInput.value;

    // Get all categories that exist for selected franchise
    const availableCategories = new Set();

    document.querySelectorAll(".menu-item").forEach(item => {
        if (item.dataset.franchise === franchise) {
            availableCategories.add(
                item.dataset.category.toLowerCase()
            );
        }
    });

    // Loop over filter buttons
    document.querySelectorAll(".filter").forEach(button => {

        const category = button.dataset.category;

        if (category === "all") {
            button.style.display = "inline-block";
            return;
        }

        if (availableCategories.has(category)) {
            button.style.display = "inline-block";
        } else {
            button.style.display = "none";
        }
    });

    // Reset active category to "all"
    activeCategory = "all";

    document.querySelectorAll(".filter").forEach(b =>
        b.classList.remove("active")
    );

    document.querySelector('[data-category="all"]').classList.add("active");
}
