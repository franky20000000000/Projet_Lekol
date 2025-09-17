document.addEventListener('DOMContentLoaded', function() {
    // 1. Sélection des éléments de l'interface
    const searchInput = document.getElementById('search-input');
    const filterMatiere = document.getElementById('filter-matiere');
    const filterNiveau = document.getElementById('filter-niveau');
    const filterVille = document.getElementById('filter-ville');
    const cardsContainer = document.getElementById('repetiteurs-container'); // Assurez-vous d'avoir un conteneur avec cet ID

    // 2. Sélection de toutes les cartes de répétiteurs
    const repetiteurCards = document.querySelectorAll('.repetiteur-card');

    // 3. Fonction de filtrage et de recherche
    function filterCards() {
        const searchTerm = searchInput.value.toLowerCase();
        const matiereFilter = filterMatiere.value.toLowerCase();
        const niveauFilter = filterNiveau.value.toLowerCase();
        const villeFilter = filterVille.value.toLowerCase();

        repetiteurCards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const matieres = card.dataset.matiere.toLowerCase();
            const niveau = card.dataset.niveau.toLowerCase();
            const ville = card.dataset.ville.toLowerCase();
            
            // Logique de correspondance
            const matchesSearch = name.includes(searchTerm);
            const matchesMatiere = matiereFilter === '' || matieres.includes(matiereFilter);
            const matchesNiveau = niveauFilter === '' || niveau === niveauFilter;
            const matchesVille = villeFilter === '' || ville === villeFilter;

            // Masquer ou afficher la carte
            if (matchesSearch && matchesMatiere && matchesNiveau && matchesVille) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // 4. Écoute des événements
    // Événement sur la saisie dans le champ de recherche
    searchInput.addEventListener('keyup', filterCards);

    // Événement sur le changement de sélection dans les filtres
    filterMatiere.addEventListener('change', filterCards);
    filterNiveau.addEventListener('change', filterCards);
    filterVille.addEventListener('change', filterCards);

    // Bonus : Bouton de réinitialisation
    const resetButton = document.querySelector('.réinitialiser-button'); // Assurez-vous de donner une classe à votre bouton de réinitialisation
    if (resetButton) {
        resetButton.addEventListener('click', () => {
            searchInput.value = '';
            filterMatiere.value = '';
            filterNiveau.value = '';
            filterVille.value = '';
            filterCards(); // Appeler la fonction pour réafficher toutes les cartes
        });
    }
});