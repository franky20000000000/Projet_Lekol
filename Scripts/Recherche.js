document.addEventListener('DOMContentLoaded', function() {
    // 1. Sélection des éléments de l'interface
    const searchInput = document.getElementById('search-input');
    const filterMatiere = document.getElementById('filter-matiere');
    const filterNiveau = document.getElementById('filter-niveau');
    const filterVille = document.getElementById('filter-ville');
    const cardsContainer = document.getElementById('repetiteurs-container');
    const searchForm = document.querySelector('form[method="GET"]');
    const resetButton = document.querySelector('a[href*="Repetiteurs.php"]');

    let currentPage = 1;
    let isLoading = false;

    // 2. Fonction de recherche AJAX
    async function searchRepetiteurs(page = 1) {
        if (isLoading) return;
        
        isLoading = true;
        const searchTerm = searchInput.value.trim();
        const matiere = filterMatiere.value;
        const niveau = filterNiveau.value;
        const ville = filterVille.value;

        try {
            const params = new URLSearchParams({
                action: 'search_repetiteurs',
                search: searchTerm,
                matiere: matiere,
                niveau: niveau,
                ville: ville,
                page: page
            });

            const response = await fetch(`admin_api.php?${params}`);
            const data = await response.json();

            if (data.success) {
                renderRepetiteurs(data.data.repetiteurs);
                currentPage = data.data.page;
                updatePagination(data.data);
            } else {
                console.error('Erreur de recherche:', data.message);
                cardsContainer.innerHTML = '<div class="col-span-2 text-center py-10"><p class="text-red-500 text-lg">Erreur lors de la recherche.</p></div>';
            }
        } catch (error) {
            console.error('Erreur de connexion:', error);
            cardsContainer.innerHTML = '<div class="col-span-2 text-center py-10"><p class="text-red-500 text-lg">Erreur de connexion.</p></div>';
        } finally {
            isLoading = false;
        }
    }

    // 3. Fonction de rendu des répétiteurs
    function renderRepetiteurs(repetiteurs) {
        if (repetiteurs.length === 0) {
            cardsContainer.innerHTML = '<div class="col-span-2 text-center py-10"><p class="text-gray-500 text-lg">Aucun répétiteur trouvé avec ces critères de recherche.</p></div>';
            return;
        }

        const isLoggedIn = document.body.classList.contains('logged-in') || window.location.pathname.includes('2.php');
        
        cardsContainer.innerHTML = repetiteurs.map(repetiteur => {
            const photo = repetiteur.photo_profil ? 
                `<img src="${repetiteur.photo_profil}" alt="photo profil" class="w-full h-full object-cover">` :
                `<img src="Images/student-7378903_1920.jpg" alt="photo profil" class="w-full h-full object-cover">`;
            
            const stars = `
                <div class="flex items-center gap-1 mt-1">
                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                    <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                </div>`;

            if (isLoggedIn) {
                return `
                    <article class="bg-gray-100 rounded-2xl p-6">
                        <a href="ProfilRepetiteurPublic.php?id=${repetiteur.id}">
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-full bg-pink-200 overflow-hidden flex items-center justify-center">
                                    ${photo}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-2xl font-semibold leading-tight">${repetiteur.prenom} ${repetiteur.nom}</h3>
                                    ${stars}
                                    <p class="text-sm text-gray-600 mt-1">${repetiteur.matieres}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 mt-4">${repetiteur.description}</p>
                        </a>
                    </article>`;
            } else {
                return `
                    <article class="bg-gray-100 rounded-2xl p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-full bg-pink-200 overflow-hidden flex items-center justify-center">
                                ${photo}
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-2xl font-semibold leading-tight">${repetiteur.prenom} ${repetiteur.nom}</h3>
                                <p class="text-sm text-gray-600 mt-1">${repetiteur.matieres}</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 mt-4">${repetiteur.description}</p>
                        <div class="mt-4">
                            <a href="Connexion.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#2B80F6] text-white hover:bg-[#1a6ad8]">
                                Se connecter pour voir le profil
                            </a>
                            <a href="Choix.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[#2B80F6] text-[#2B80F6] hover:bg-[#2B80F6] hover:text-white ml-2">
                                Créer un compte
                            </a>
                        </div>
                    </article>`;
            }
        }).join('');
    }

    // 4. Fonction de mise à jour de la pagination
    function updatePagination(data) {
        const paginationContainer = document.querySelector('.pagination-container');
        if (!paginationContainer) return;

        if (data.total_pages > 1 && data.page < data.total_pages) {
            paginationContainer.innerHTML = `
                <div class="flex justify-center mt-6 mb-6">
                    <button onclick="loadMore()" class="bg-[#2B80F6] p-2 px-5 rounded-lg text-white shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-[#2B80F6]-400/50">
                        Voir plus
                    </button>
                </div>
            `;
        } else {
            paginationContainer.innerHTML = '';
        }
    }

    // 5. Fonction pour charger plus de résultats
    window.loadMore = function() {
        searchRepetiteurs(currentPage + 1);
    };

    // 6. Fonction de recherche avec délai (debounce)
    let searchTimeout;
    function debouncedSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchRepetiteurs(1);
        }, 300);
    }

    // 7. Écoute des événements
    searchInput.addEventListener('input', debouncedSearch);
    filterMatiere.addEventListener('change', () => searchRepetiteurs(1));
    filterNiveau.addEventListener('change', () => searchRepetiteurs(1));
    filterVille.addEventListener('change', () => searchRepetiteurs(1));

    // Empêcher la soumission du formulaire
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            searchRepetiteurs(1);
        });
    }

    // Bouton de réinitialisation
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            searchInput.value = '';
            filterMatiere.value = '';
            filterNiveau.value = '';
            filterVille.value = '';
            searchRepetiteurs(1);
        });
    }

    // 8. Chargement initial
    searchRepetiteurs(1);
});