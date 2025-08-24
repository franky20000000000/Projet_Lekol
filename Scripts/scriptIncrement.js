const repetiteur= document.getElementById('repetiteur');
const eleves = document.getElementById('eleves');
const taux = document.getElementById('taux');
const villes = document.getElementById('villes');

    
// Fonction pour incrémenter un nombre avec un intervalle
  function incrementNumber(a,b,c) {
    let repetiteurs=0;

    let intRepetiteur= setInterval(() => {      
        repetiteurs++;    
    a.textContent= repetiteurs+c;
        if (repetiteurs==b) {
            clearInterval(intRepetiteur);
                     
        }
    }, 10);
}
incrementNumber(repetiteur,100,'+');
incrementNumber(eleves,150,'+');
incrementNumber(taux,90,'%');
incrementNumber(villes,8,'+')

// --- Fin de l'incrémentation des nombres