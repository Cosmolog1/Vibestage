/* JavaScript du formulaire login */

document.addEventListener("DOMContentLoaded", function () {
    const signUpButton = document.getElementById("signUp");
    const signInButton = document.getElementById("signIn");
    const container = document.getElementById("container");

    if (!signUpButton || !signInButton || !container) {
        return; // on ne fait rien si les éléments n'existent pas
    }

    signUpButton.addEventListener("click", () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener("click", () => {
        container.classList.remove("right-panel-active");
        // location.reload(); ← enlève ça si tu veux garder l’animation
    });
});
