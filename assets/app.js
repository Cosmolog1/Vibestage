import "./bootstrap.js";
import "./styles/app.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
const card = document.querySelector(".card");
/* Animation image */
function applyClickAnimation(card) {
    card.classList.remove("subtle-rotate");
    card.offsetHeight; // Force le reflow
    card.classList.add("subtle-rotate");

    setTimeout(() => {
        card.classList.remove("subtle-rotate");
    }, 650);
}

// Au clic
card.addEventListener("click", function (e) {
    applyClickAnimation(e.target.value);
});
