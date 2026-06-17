document.addEventListener("DOMContentLoaded", function () {

    const wifiToggle = document.getElementById("wifiToggle");
    const wifiModal = document.getElementById("wifiModal");
    const wifiClose = document.getElementById("wifiClose");

    if (wifiToggle && wifiModal) {
        wifiToggle.addEventListener("click", function () {
            wifiModal.classList.add("active");
        });
    }

    if (wifiClose && wifiModal) {
        wifiClose.addEventListener("click", function () {
            wifiModal.classList.remove("active");
        });
    }

    const backdrop = document.querySelector("#wifiModal .modal-backdrop");
    if (backdrop && wifiModal) {
        backdrop.addEventListener("click", function () {
            wifiModal.classList.remove("active");
        });
    }

});
