// Répétiteurs management functionality
const Repetiteurs = {
    data: [
        {
            id: 1,
            nom: 'Marie Dubois',
            email: 'marie.dubois@email.com',
            telephone: '0123456789',
            statut: 'actif',
            abonnement: 'premium',
            notemoyenne: 4.8,
            matiere: 'Mathématiques',
            niveau: 'Lycée',
            dateInscription: '2024-01-15',
            derniereConnexion: '2024-01-20'
        },
        {
            id: 2,
            nom: 'Jean Martin',
            email: 'jean.martin@email.com',
            telephone: '0123456790',
            statut: 'inactif',
            abonnement: 'basique',
            notemoyenne: 4.2,
            matiere: 'Français',
            niveau: 'Collège',
            dateInscription: '2024-01-10',
            derniereConnexion: '2024-01-18'
        },
        {
            id: 3,
            nom: 'Sophie Bernard',
            email: 'sophie.bernard@email.com',
            telephone: '0123456791',
            statut: 'suspendu',
            abonnement: 'premium',
            notemoyenne: 3.9,
            matiere: 'Anglais',
            niveau: 'Primaire',
            dateInscription: '2024-01-05',
            derniereConnexion: '2024-01-19'
        }
    ],
    
    // Données pour les demandes de validation
    demandesValidation: [
        {
            id: 1001,
            nom: 'Dupont',
            prenom: 'Paul',
            email: 'paul.dupont@email.com',
            telephone: '0612345678',
            dateNaissance: '1995-05-15',
            adresse: '12 Rue des Écoles, 75005 Paris',
            diplomes: [
                { nom: 'Master Mathématiques', fichier: 'diplome_math.pdf' },
                { nom: 'Licence Physique', fichier: 'diplome_physique.pdf' }
            ],
            piecesIdentite: [
                { type: 'Carte d\'identité', fichier: 'ci_paul_dupont.pdf' },
                { type: 'Justificatif de domicile', fichier: 'domicile_paul.pdf' }
            ],
            matiere: 'Mathématiques',
            niveau: 'Lycée',
            experience: '5 ans',
            dateDemande: '2024-01-25',
            statut: 'en_attente'
        },
        {
            id: 1002,
            nom: 'Martin',
            prenom: 'Julie',
            email: 'julie.martin@email.com',
            telephone: '0698765432',
            dateNaissance: '1992-08-22',
            adresse: '45 Avenue Victor Hugo, 75016 Paris',
            diplomes: [
                { nom: 'Doctorat Chimie', fichier: 'diplome_chimie.pdf' }
            ],
            piecesIdentite: [
                { type: 'Passeport', fichier: 'passeport_julie.pdf' }
            ],
            matiere: 'Chimie',
            niveau: 'Université',
            experience: '8 ans',
            dateDemande: '2024-01-24',
            statut: 'en_attente',
            complementDemande: 'Veuillez fournir votre attestation d\'assurance responsabilité civile.'
        }
    ],
    
    filteredData: [],
    currentFilter: {
        search: '',
        statut: 'tous'
    },
    
    init() {
        this.render();
        this.bindEvents();
        this.filteredData = [...this.data];
        this.renderTable();
        this.renderValidationTable();
    },
    
    render() {
        const section = document.getElementById('repetiteurs-section');
        section.innerHTML = `
            <div class="space-y-6">
                <!-- Header avec onglets -->
                <div class="flex justify-between items-center border-b border-gray-200">
                    <div class="flex space-x-4">
                        <button id="tab-gestion" class="tab-button active">
                            Gestion des Répétiteurs
                        </button>
                        <button id="tab-validation" class="tab-button">
                            Validation des Comptes
                        </button>
                    </div>
                    <button onclick="Repetiteurs.showAddModal()" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un répétiteur
                    </button>
                </div>

                <!-- Contenu de l'onglet Gestion -->
                <div id="gestion-content">
                    <!-- Filters -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" id="search-repetiteurs" placeholder="Rechercher par nom ou email..." 
                                           class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <select id="filter-statut" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="tous">Tous les statuts</option>
                                    <option value="actif">Actif</option>
                                    <option value="inactif">Inactif</option>
                                    <option value="suspendu">Suspendu</option>
                                </select>
                                <button class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-filter mr-2"></i>
                                    Filtres
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-black">Liste des répétiteurs (<span id="count-repetiteurs">${this.data.length}</span>)</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-50">
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Répétiteur</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Contact</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Matière/Niveau</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Statut</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Abonnement</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Note</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="repetiteurs-table-body">
                                    <!-- Content will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Contenu de l'onglet Validation -->
                <div id="validation-content" class="hidden">
                    <!-- Tableau des demandes en attente -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-black">Demandes de création de comptes en attente (<span id="count-demandes">${this.demandesValidation.length}</span>)</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-50">
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Candidat</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Informations personnelles</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Diplômes</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Pièces jointes</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Date de demande</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="demandes-table-body">
                                    <!-- Content will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Initialiser les onglets
        this.setupTabs();
    },
    
    setupTabs() {
        const tabGestion = document.getElementById('tab-gestion');
        const tabValidation = document.getElementById('tab-validation');
        const contentGestion = document.getElementById('gestion-content');
        const contentValidation = document.getElementById('validation-content');
        
        tabGestion.addEventListener('click', () => {
            tabGestion.classList.add('active');
            tabValidation.classList.remove('active');
            contentGestion.classList.remove('hidden');
            contentValidation.classList.add('hidden');
        });
        
        tabValidation.addEventListener('click', () => {
            tabValidation.classList.add('active');
            tabGestion.classList.remove('active');
            contentValidation.classList.remove('hidden');
            contentGestion.classList.add('hidden');
        });
    },
    
    bindEvents() {
        // Search functionality
        document.addEventListener('input', (e) => {
            if (e.target.id === 'search-repetiteurs') {
                this.currentFilter.search = e.target.value;
                this.applyFilters();
            }
        });
        
        // Filter functionality
        document.addEventListener('change', (e) => {
            if (e.target.id === 'filter-statut') {
                this.currentFilter.statut = e.target.value;
                this.applyFilters();
            }
        });
    },
    
    applyFilters() {
        this.filteredData = this.data.filter(repetiteur => {
            const matchesSearch = this.currentFilter.search === '' || 
                repetiteur.nom.toLowerCase().includes(this.currentFilter.search.toLowerCase()) ||
                repetiteur.email.toLowerCase().includes(this.currentFilter.search.toLowerCase());
            
            const matchesStatut = this.currentFilter.statut === 'tous' || 
                repetiteur.statut === this.currentFilter.statut;
            
            return matchesSearch && matchesStatut;
        });
        
        this.renderTable();
        this.updateCount();
    },
    
    renderTable() {
        const tbody = document.getElementById('repetiteurs-table-body');
        if (!tbody) return;
        
        tbody.innerHTML = this.filteredData.map(repetiteur => `
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-4 px-4">
                    <div>
                        <p class="font-medium text-black">${repetiteur.nom}</p>
                        <p class="text-sm text-gray-600">ID: ${repetiteur.id}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div>
                        <p class="text-sm text-black">${repetiteur.email}</p>
                        <p class="text-sm text-gray-600">${repetiteur.telephone}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div>
                        <p class="text-sm font-medium text-black">${repetiteur.matiere}</p>
                        <p class="text-sm text-gray-600">${repetiteur.niveau}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <span class="px-2 py-1 text-xs rounded-full ${this.getStatutClass(repetiteur.statut)}">
                        ${repetiteur.statut}
                    </span>
                </td>
                <td class="py-4 px-4">
                    <span class="px-2 py-1 text-xs rounded-full ${this.getAbonnementClass(repetiteur.abonnement)}">
                        ${repetiteur.abonnement}
                    </span>
                </td>
                <td class="py-4 px-4">
                    <div class="flex items-center space-x-1">
                        <span class="font-medium">${repetiteur.notemoyenne}</span>
                        <i class="fas fa-star text-yellow-500"></i>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div class="flex items-center space-x-2">
                        <button onclick="Repetiteurs.showDetailModal(${repetiteur.id})" 
                                class="p-2 text-gray-600 hover:text-primary transition-colors" 
                                title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="Repetiteurs.showEditModal(${repetiteur.id})" 
                                class="p-2 text-gray-600 hover:text-primary transition-colors" 
                                title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="Repetiteurs.toggleStatus(${repetiteur.id}, 'actif')" 
                                class="p-2 text-green-600 hover:text-green-700 transition-colors" 
                                title="Valider">
                            <i class="fas fa-check-circle"></i>
                        </button>
                        <button onclick="Repetiteurs.toggleStatus(${repetiteur.id}, 'suspendu')" 
                                class="p-2 text-red-600 hover:text-red-700 transition-colors" 
                                title="Suspendre">
                            <i class="fas fa-times-circle"></i>
                        </button>
                        <button onclick="Repetiteurs.deleteRepetiteur(${repetiteur.id})" 
                                class="p-2 text-red-600 hover:text-red-700 transition-colors" 
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    },
    
    renderValidationTable() {
        const tbody = document.getElementById('demandes-table-body');
        if (!tbody) return;
        
        tbody.innerHTML = this.demandesValidation.map(demande => `
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-4 px-4">
                    <div>
                        <p class="font-medium text-black">${demande.prenom} ${demande.nom}</p>
                        <p class="text-sm text-gray-600">${demande.email}</p>
                        <p class="text-sm text-gray-600">${demande.telephone}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div>
                        <p class="text-sm text-black">Né(e) le: ${this.formatDate(demande.dateNaissance)}</p>
                        <p class="text-sm text-black">Adresse: ${demande.adresse}</p>
                        <p class="text-sm text-black">Matière: ${demande.matiere}</p>
                        <p class="text-sm text-black">Niveau: ${demande.niveau}</p>
                        <p class="text-sm text-black">Expérience: ${demande.experience}</p>
                        ${demande.complementDemande ? `
                            <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-sm text-yellow-800 font-semibold">Demande de complément:</p>
                                <p class="text-sm text-yellow-700">${demande.complementDemande}</p>
                            </div>
                        ` : ''}
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div class="space-y-1">
                        ${demande.diplomes.map(diplome => `
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                <span class="text-sm text-gray-700">${diplome.nom}</span>
                                <button onclick="Repetiteurs.viewDocument('${diplome.fichier}')" class="ml-2 text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        `).join('')}
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div class="space-y-1">
                        ${demande.piecesIdentite.map(piece => `
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                <span class="text-sm text-gray-700">${piece.type}</span>
                                <button onclick="Repetiteurs.viewDocument('${piece.fichier}')" class="ml-2 text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        `).join('')}
                    </div>
                </td>
                <td class="py-4 px-4">
                    <p class="text-sm text-black">${this.formatDate(demande.dateDemande)}</p>
                </td>
                <td class="py-4 px-4">
                    <div class="flex flex-col space-y-2">
                        <button onclick="Repetiteurs.validerDemande(${demande.id})" 
                                class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition-colors text-sm">
                            <i class="fas fa-check mr-1"></i> Valider
                        </button>
                        <button onclick="Repetiteurs.rejeterDemande(${demande.id})" 
                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors text-sm">
                            <i class="fas fa-times mr-1"></i> Rejeter
                        </button>
                        <button onclick="Repetiteurs.demanderComplements(${demande.id})" 
                                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors text-sm">
                            <i class="fas fa-question mr-1"></i> Compléments
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    },
    
    updateCount() {
        const countElement = document.getElementById('count-repetiteurs');
        if (countElement) {
            countElement.textContent = this.filteredData.length;
        }
    },
    
    getStatutClass(statut) {
        switch (statut) {
            case 'actif': return 'bg-green-100 text-green-800';
            case 'inactif': return 'bg-gray-100 text-gray-800';
            case 'suspendu': return 'bg-red-100 text-red-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    },
    
    getAbonnementClass(abonnement) {
        switch (abonnement) {
            case 'premium': return 'bg-purple-100 text-purple-800';
            case 'basique': return 'bg-blue-100 text-blue-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    },
    
    formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('fr-FR', options);
    },
    
    viewDocument(filename) {
        // Dans une implémentation réelle, cela ouvrirait le document
        alert(`Visualisation du document: ${filename}\n\nDans une implémentation réelle, ce document serait affiché ou téléchargé.`);
    },
    
    validerDemande(id) {
        if (confirm('Êtes-vous sûr de vouloir valider ce compte répétiteur ?')) {
            const demande = this.demandesValidation.find(d => d.id === id);
            if (!demande) return;
            
            // Envoyer la validation au serveur
            this.submitForm({ demande_id: id }, 'admin/repetiteurs/valider.php', () => {
                // Ajouter aux répétiteurs actifs
                this.data.push({
                    id: this.data.length + 1,
                    nom: `${demande.prenom} ${demande.nom}`,
                    email: demande.email,
                    telephone: demande.telephone,
                    statut: 'actif',
                    abonnement: 'basique',
                    notemoyenne: 0,
                    matiere: demande.matiere,
                    niveau: demande.niveau,
                    dateInscription: new Date().toISOString().split('T')[0],
                    derniereConnexion: new Date().toISOString().split('T')[0]
                });
                
                // Supprimer de la liste des demandes
                this.demandesValidation = this.demandesValidation.filter(d => d.id !== id);
                this.renderValidationTable();
                this.updateValidationCount();
                
                alert('Compte validé avec succès !');
            });
        }
    },
    
    rejeterDemande(id) {
        const raison = prompt('Veuillez saisir la raison du rejet :');
        if (raison !== null) {
            if (raison.trim() === '') {
                alert('Veuillez saisir une raison pour le rejet.');
                return;
            }
            
            // Envoyer le rejet au serveur
            this.submitForm({ demande_id: id, raison: raison }, 'admin/repetiteurs/rejeter.php', () => {
                // Supprimer de la liste des demandes
                this.demandesValidation = this.demandesValidation.filter(d => d.id !== id);
                this.renderValidationTable();
                this.updateValidationCount();
                
                alert('Demande rejetée avec succès !');
            });
        }
    },
    
    demanderComplements(id) {
        const message = prompt('Quels compléments d\'information souhaitez-vous demander ?');
        if (message !== null) {
            if (message.trim() === '') {
                alert('Veuillez saisir un message.');
                return;
            }
            
            // Envoyer la demande de compléments au serveur
            this.submitForm({ demande_id: id, message: message }, 'admin/repetiteurs/complements.php', () => {
                // Mettre à jour la demande avec la demande de complément
                const demande = this.demandesValidation.find(d => d.id === id);
                if (demande) {
                    demande.complementDemande = message;
                    this.renderValidationTable();
                }
                
                alert('Demande de compléments envoyée avec succès !');
            });
        }
    },
    
    updateValidationCount() {
        const countElement = document.getElementById('count-demandes');
        if (countElement) {
            countElement.textContent = this.demandesValidation.length;
        }
    },
    
    submitForm(data, url, callback) {
        // Simulation d'envoi au serveur
        console.log('Envoi des données à', url, data);
        setTimeout(callback, 500);
    },
    
    // Les autres méthodes existantes (showDetailModal, showEditModal, etc.) restent inchangées
    // ... (le reste de vos méthodes existantes)
};

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    Repetiteurs.init();
});