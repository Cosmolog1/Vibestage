import "./bootstrap.js";
import "./styles/app.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

// Fonction pour charger les titres Deezer via JSONP
function loadDeezerTracks(artistId) {
    const trackListEl = document.getElementById("trackList");
    if (!trackListEl) return;

    // Supprime un éventuel ancien script pour éviter les doublons
    const oldScript = document.getElementById("deezerScript");
    if (oldScript) oldScript.remove();

    // Crée le script JSONP avec callback global
    const script = document.createElement("script");
    script.id = "deezerScript";
    script.src = `https://api.deezer.com/artist/${artistId}/top?limit=6&output=jsonp&callback=displayTracks`;

    document.body.appendChild(script);
}

// Callback appelé par Deezer JSONP
window.displayTracks = function (response) {
    const trackListEl = document.getElementById("trackList");
    if (!trackListEl) return;

    if (!response.data || response.data.length === 0) {
        trackListEl.innerHTML = "<li>Aucun titre disponible</li>";
        return;
    }

    const html = response.data
        .map(
            (track) => `
                <div class="col-md-5 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">${track.title_short}</h5>
                            <p class="card-text"><em>Album :</em> ${track.album.title}</p>
                            <audio controls src="${track.preview}" class="w-100"></audio>
                        </div>
                    </div>
                </div>
            `
        )
        .join("");

    trackListEl.innerHTML = `<div class="row">${html}</div>`;
};

// Lancement au chargement de la page
document.addEventListener("DOMContentLoaded", () => {
    const artistDataEl = document.getElementById("artistData");
    if (!artistDataEl) return;

    const artistId = artistDataEl.dataset.id;
    loadDeezerTracks(artistId);
});
