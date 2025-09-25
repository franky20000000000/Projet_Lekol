// Fonctionnalité de gestion des abonnements
const Abonnements = {
    // Données des abonnements
    data: [],
    
    // Données filtrées pour l'affichage
    filteredData: [],
    
    // Filtres actuels appliqués
    currentFilter: {
        search: '',
        statut: 'tous'
    },
    
    // Initialisation du composant
    init() {
        this.render();
        this.bindEvents();
        this.fetch();
    },

    fetch() {
        // TODO: brancher à un endpoint (ex: list_abonnements) quand disponible
        this.data = [];
        this.filteredData = [];
        this.renderTable();
        this.loadStats();
    },
    
    // Rendu de l'interface
    render() {
        const section = document.getElementById('abonnements-section');
        section.innerHTML = `
            <div class="space-y-6">
                <!-- En-tête -->
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-black">Gestion des Abonnements</h2>
                </div>

                <!-- Cartes de statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Abonnements Actifs</p>
                                <p class="text-2xl font-bold text-black" id="stat-actifs">0</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded-lg">
                                <i class="fas fa-check-circle text-xl text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Revenus Mensuels</p>
                                <p class="text-2xl font-bold text-black" id="stat-revenus">0 FCFA</p>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-money-bill-wave text-xl text-primary"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Expiration Proche</p>
                                <p class="text-2xl font-bold text-black" id="stat-expire-bientot">0</p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-xl text-orange-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="search-abonnements" placeholder="Rechercher par nom de répétiteur..." 
                                       class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select id="filter-statut-abonnement" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="tous">Tous les statuts</option>
                                <option value="payé">Payé</option>
                                <option value="en attente">En attente</option>
                                <option value="expiré">Expiré</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tableau -->
                <div class="bg-white border border-gray-200 rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-black">Liste des abonnements (<span id="count-abonnements">${this.data.length}</span>)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50">
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Répétiteur</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Type</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Montant</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Date début</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Date fin</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Statut</th>
                                </tr>
                            </thead>
                            <tbody id="abonnements-table-body">
                                <!-- Le contenu sera rempli par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
    },
    
    // Liaison des événements
    bindEvents() {
        // Fonctionnalité de recherche
        document.addEventListener('input', (e) => {
            if (e.target.id === 'search-abonnements') {
                this.currentFilter.search = e.target.value;
                this.applyFilters();
            }
        });
        
        // Fonctionnalité de filtre
        document.addEventListener('change', (e) => {
            if (e.target.id === 'filter-statut-abonnement') {
                this.currentFilter.statut = e.target.value;
                this.applyFilters();
            }
        });
    },
    
    // Chargement des statistiques
    loadStats() {
        // Compter les abonnements actifs (statut "payé")
        const actifs = this.data.filter(a => a.statut === 'payé').length;
        
        // Calculer le total des revenus des abonnements payés
        const revenus = this.data
            .filter(a => a.statut === 'payé')
            .reduce((sum, a) => sum + a.prix, 0);
        
        // Calculer les abonnements qui expirent dans les 7 prochains jours
        const aujourdHui = new Date();
        const dans7Jours = new Date(aujourdHui.getTime() + 7 * 24 * 60 * 60 * 1000);
        const expireBientot = this.data.filter(a => {
            const dateFin = new Date(a.date_fin);
            return a.statut === 'payé' && dateFin <= dans7Jours && dateFin >= aujourdHui;
        }).length;
        
        // Mettre à jour l'affichage des statistiques
        document.getElementById('stat-actifs').textContent = actifs;
        document.getElementById('stat-revenus').textContent = `${(revenus||0).toLocaleString()} FCFA`;
        document.getElementById('stat-expire-bientot').textContent = expireBientot;
    },
    
    // Application des filtres
    applyFilters() {
        this.filteredData = this.data.filter(abonnement => {
            // Vérifier si l'abonnement correspond à la recherche
            const correspondRecherche = this.currentFilter.search === '' || 
                abonnement.repetiteur_nom.toLowerCase().includes(this.currentFilter.search.toLowerCase());
            
            // Vérifier si l'abonnement correspond au statut filtré
            const correspondStatut = this.currentFilter.statut === 'tous' || 
                abonnement.statut === this.currentFilter.statut;
            
            return correspondRecherche && correspondStatut;
        });
        
        this.renderTable();
        this.updateCount();
    },
    
    // Rendu du tableau
    renderTable() {
        const tbody = document.getElementById('abonnements-table-body');
        if (!tbody) return;
        
        tbody.innerHTML = this.filteredData.map(abonnement => `
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-4 px-4">
                    <div>
                        <p class="font-medium text-black">${abonnement.repetiteur_nom}</p>
                        <p class="text-sm text-gray-600">${abonnement.repetiteur_telephone}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <span class="px-2 py-1 text-xs rounded-full ${this.getClassType(abonnement.type)}">
                        ${abonnement.type}
                    </span>
                </td>
                <td class="py-4 px-4">
                    <div>
                        <p class="font-medium text-black">${abonnement.prix.toLocaleString()} ${abonnement.devise}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <p class="text-sm text-black">${this.formaterDate(abonnement.date_debut)}</p>
                </td>
                <td class="py-4 px-4">
                    <p class="text-sm text-black">${this.formaterDate(abonnement.date_fin)}</p>
                </td>
                <td class="py-4 px-4">
                    <span class="px-2 py-1 text-xs rounded-full ${this.getClassStatut(abonnement.statut)}">
                        ${abonnement.statut}
                    </span>
                </td>
            </tr>
        `).join('');
    },
    
    // Mise à jour du compteur d'abonnements
    updateCount() {
        const elementCompteur = document.getElementById('count-abonnements');
        if (elementCompteur) {
            elementCompteur.textContent = this.filteredData.length;
        }
    },
    
    // Obtenir la classe CSS pour le statut
    getClassStatut(statut) {
        switch (statut) {
            case 'payé': return 'bg-green-100 text-green-800';
            case 'expiré': return 'bg-red-100 text-red-800';
            case 'en attente': return 'bg-yellow-100 text-yellow-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    },
    
    // Obtenir la classe CSS pour le type
    getClassType(type) {
        switch (type) {
            case 'Trimestriel': return 'bg-purple-100 text-purple-800';
            case 'Mensuel': return 'bg-blue-100 text-blue-800';
            case 'Annuel': return 'bg-green-100 text-green-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    },
    
    // Formater une date au format français
    formaterDate(chaineDate) {
        const date = new Date(chaineDate);
        return date.toLocaleDateString('fr-FR');
    }
};

// Initialisation lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    Abonnements.init();
});