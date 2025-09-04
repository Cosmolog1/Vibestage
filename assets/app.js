import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
// import "./vendor/bootstrap/dist/css/bootstrap.min.css";
// import "bootstrap";
import "./styles/app.css";

import "./scripts/login.js";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

/* Animation image */

const cards = document.querySelectorAll(".card-artiste");

cards.forEach((card) => {
    card.addEventListener("click", function () {
        this.classList.add("subtle-rotate");
        setTimeout(() => this.classList.remove("subtle-rotate"), 600);
    });
});
