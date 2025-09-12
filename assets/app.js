import "./bootstrap.js";
import "./styles/app.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

const card = document.querySelectorAll(".card-artiste");

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
// card.addEventListener("click", function (e) {
//     applyClickAnimation(e.target.value);
// });

// Api deezer //

document.addEventListener("turbo:load", async () => {
    const artistDataEl = document.getElementById("artistData");
    if (!artistDataEl) return; // On quitte si pas sur la page single artiste
    const artistId = artistDataEl.dataset.id;

    const trackListEl = document.getElementById("trackList");

    const res = await fetch(`/api/deezer/artist/search/${artistId}`);

    const tracks = await res.json();

    if (!tracks || tracks.length === 0) {
        trackListEl.innerHTML = "<li>Aucun titre disponible</li>";
        return;
    }

    const html = tracks
        .map(
            (track) => `
            <li class="mb-4">
                <img src="${track.album.cover_medium}" width="50" alt="cover">
                <strong>${track.title}</strong> - ${track.album.title}<br>
                <audio controls src="${track.preview}"></audio>
            </li>
        `
        )
        .join("");

    trackListEl.innerHTML = html;
});

// document.addEventListener("DOMContentLoaded", async () => {

// });
