function toggleDropdown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

function selectLocation(id, name) {
    document.querySelector(".dropdown-btn").innerText = name;
    document.getElementById("franchise_id").value = id;
    document.getElementById("myDropdown").classList.remove("show");
}
