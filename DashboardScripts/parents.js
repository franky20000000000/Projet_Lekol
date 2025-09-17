// Fonctionnalité de gestion des parents
const Parents = {
    // Données des parents
    data: [
        {
            id: 1,
            nom: 'Pierre Dupont',
            email: 'pierre.dupont@email.com',
            telephone: '0123456789',
            dateInscription: '2024-01-15',
            derniereConnexion: '2024-01-20',
            nombreContacts: 5
        },
        {
            id: 2,
            nom: 'Isabelle Martin',
            email: 'isabelle.martin@email.com',
            telephone: '0123456790',
            dateInscription: '2024-01-10',
            derniereConnexion: '2024-01-19',
            nombreContacts: 3
        },
        {
            id: 3,
            nom: 'Thomas Bernard',
            email: 'thomas.bernard@email.com',
            telephone: '0123456791',
            dateInscription: '2024-01-05',
            derniereConnexion: '2024-01-18',
            nombreContacts: 8
        }
    ],
    
    // Données filtrées pour l'affichage
    filteredData: [],
    
    // Filtres actuels appliqués
    currentFilter: {
        search: ''
    },
    
    // Initialisation du composant
    init() {
        this.render();
        this.bindEvents();
        this.filteredData = [...this.data];
        this.renderTable();
        this.loadStats();
    },
    
    // Rendu de l'interface
    render() {
        const section = document.getElementById('parents-section');
        section.innerHTML = `
            <div class="space-y-6">
                <!-- En-tête -->
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-black">Gestion des Parents</h2>
                </div>

                <!-- Cartes de statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Total Parents</p>
                                <p class="text-2xl font-bold text-black" id="stat-parents">${this.data.length}</p>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-users text-xl text-primary"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Contacts Total</p>
                                <p class="text-2xl font-bold text-black" id="stat-contacts">0</p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded-lg">
                                <i class="fas fa-phone text-xl text-orange-600"></i>
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
                                <input type="text" id="search-parents" placeholder="Rechercher par nom ou email..." 
                                       class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau -->
                <div class="bg-white border border-gray-200 rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-black">Liste des parents (<span id="count-parents">${this.data.length}</span>)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50">
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Parent</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Contact</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Inscription</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Contacts</th>
                                </tr>
                            </thead>
                            <tbody id="parents-table-body">
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
            if (e.target.id === 'search-parents') {
                this.currentFilter.search = e.target.value;
                this.applyFilters();
            }
        });
    },
    
    // Chargement des statistiques
    loadStats() {
        // Calculer le total des contacts
        const totalContacts = this.data.reduce((total, parent) => total + parent.nombreContacts, 0);
        
        // Mettre à jour l'affichage des statistiques
        document.getElementById('stat-contacts').textContent = totalContacts;
    },
    
    // Application des filtres
    applyFilters() {
        this.filteredData = this.data.filter(parent => {
            // Vérifier si le parent correspond à la recherche
            const correspondRecherche = this.currentFilter.search === '' || 
                parent.nom.toLowerCase().includes(this.currentFilter.search.toLowerCase()) ||
                parent.email.toLowerCase().includes(this.currentFilter.search.toLowerCase());
            
            return correspondRecherche;
        });
        
        this.renderTable();
        this.updateCount();
    },
    
    // Rendu du tableau
    renderTable() {
        const tbody = document.getElementById('parents-table-body');
        if (!tbody) return;
        
        tbody.innerHTML = this.filteredData.map(parent => `
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-4 px-4">
                    <div>
                        <p class="font-medium text-black">${parent.nom}</p>
                        <p class="text-sm text-gray-600">ID: ${parent.id}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-envelope text-gray-400 text-sm"></i>
                            <span class="text-sm text-black">${parent.email}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-phone text-gray-400 text-sm"></i>
                            <span class="text-sm text-gray-600">${parent.telephone}</span>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div>
                        <p class="text-sm text-black">${this.formaterDate(parent.dateInscription)}</p>
                        <p class="text-xs text-gray-600">Dernière: ${this.formaterDate(parent.derniereConnexion)}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                        ${parent.nombreContacts} contacts
                    </span>
                </td>
            </tr>
        `).join('');
    },
    
    // Mise à jour du compteur de parents
    updateCount() {
        const elementCompteur = document.getElementById('count-parents');
        if (elementCompteur) {
            elementCompteur.textContent = this.filteredData.length;
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
    Parents.init();
});