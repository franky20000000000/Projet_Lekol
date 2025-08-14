const repetiteur= document.getElementById('repetiteur');
const eleves = document.getElementById('eleves');
const taux = document.getElementById('taux');
const villes = document.getElementById('villes');

    
function incrementNumber(a,b,c) {
    let repetiteurs=0;

    let intRepetiteur= setInterval(() => {      
        repetiteurs++;    
    a.textContent= repetiteurs+c;
        if (repetiteurs==b) {
            clearInterval(intRepetiteur);
            console.log('stop');            
        }
    }, 10);
}

incrementNumber(repetiteur,100,'+');
incrementNumber(eleves,150,'+');
incrementNumber(taux,90,'%');
incrementNumber(villes,8,'+')