const repetiteur = document.getElementById('repetiteur');
const eleves = document.getElementById('eleves');
const taux = document.getElementById('taux');
const villes = document.getElementById('villes');

// Fonction pour incrémenter un nombre avec un intervalle
function incrementNumber(element, max, suffix) {
    let value = 0;
    const interval = setInterval(() => {
        value++;
        element.textContent = value + suffix;
        if (value >= max) clearInterval(interval);
    }, 10);
}

// Observer pour déclencher l'incrémentation quand la section devient visible
const section = document.querySelector('section.w-full'); // sélectionne ta section
let hasRun = false;

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if(entry.isIntersecting && !hasRun) {
            incrementNumber(repetiteur, 100, '+');
            incrementNumber(eleves, 150, '+');
            incrementNumber(taux, 90, '%');
            incrementNumber(villes, 8, '+');
            hasRun = true; // empêche de relancer
        }
    });
}, { threshold: 0.5 }); // déclenche quand 50% de la section est visible

observer.observe(section);


