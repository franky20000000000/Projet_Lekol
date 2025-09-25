// Contenus management functionality
const Contenus = {
    data: [],
    
    filteredData: [],
    currentFilter: {
        search: '',
        type: 'tous',
        matiere: 'toutes'
    },
    
    init() {
        this.render();
        this.bindEvents();
        this.fetch();
    },

    fetch() {
        // TODO: brancher à un endpoint (ex: list_contenus) quand disponible
        this.data = [];
        this.filteredData = [];
        this.renderTable();
        this.loadStats();
    },
    
    render() {
        const section = document.getElementById('contenus-section');
        section.innerHTML = `
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-black">Gestion des Contenus Éducatifs</h2>
                    <button onclick="Contenus.showAddModal()" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un contenu
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Total Contenus</p>
                                <p class="text-2xl font-bold text-black" id="stat-total">${this.data.length}</p>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-file-alt text-xl text-primary"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Téléchargements</p>
                                <p class="text-2xl font-bold text-black" id="stat-downloads">0</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded-lg">
                                <i class="fas fa-download text-xl text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Publiés</p>
                                <p class="text-2xl font-bold text-black" id="stat-publies">0</p>
                            </div>
                            <div class="p-3 bg-purple-50 rounded-lg">
                                <i class="fas fa-eye text-xl text-purple-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Brouillons</p>
                                <p class="text-2xl font-bold text-black" id="stat-brouillons">0</p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded-lg">
                                <i class="fas fa-edit text-xl text-orange-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="search-contenus" placeholder="Rechercher par titre ou description..." 
                                       class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select id="filter-type-contenu" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="tous">Tous types</option>
                                <option value="pdf">PDF</option>
                                <option value="video">Vidéo</option>
                                <option value="image">Image</option>
                            </select>
                            <select id="filter-matiere-contenu" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="toutes">Toutes matières</option>
                                <option value="Mathématiques">Mathématiques</option>
                                <option value="Français">Français</option>
                                <option value="Sciences">Sciences</option>
                                <option value="Anglais">Anglais</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white border border-gray-200 rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-black">Liste des contenus (<span id="count-contenus">${this.data.length}</span>)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50">
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Contenu</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Type</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Matière/Niveau</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Auteur</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Statut</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Téléchargements</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="contenus-table-body">
                                <!-- Content will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
    },
    
    bindEvents() {
        // Search functionality
        document.addEventListener('input', (e) => {
            if (e.target.id === 'search-contenus') {
                this.currentFilter.search = e.target.value;
                this.applyFilters();
            }
        });
        
        // Filter functionality
        document.addEventListener('change', (e) => {
            if (e.target.id === 'filter-type-contenu') {
                this.currentFilter.type = e.target.value;
                this.applyFilters();
            } else if (e.target.id === 'filter-matiere-contenu') {
                this.currentFilter.matiere = e.target.value;
                this.applyFilters();
            }
        });
    },
    
    loadStats() {
        const totalDownloads = this.data.reduce((total, contenu) => total + (contenu.telechargements||0), 0);
        const publies = this.data.filter(c => c.statut === 'publié').length;
        const brouillons = this.data.filter(c => c.statut === 'brouillon').length;
        
        document.getElementById('stat-downloads').textContent = totalDownloads;
        document.getElementById('stat-publies').textContent = publies;
        document.getElementById('stat-brouillons').textContent = brouillons;
    },
    
    applyFilters() {
        this.filteredData = this.data.filter(contenu => {
            const matchesSearch = this.currentFilter.search === '' || 
                contenu.titre.toLowerCase().includes(this.currentFilter.search.toLowerCase()) ||
                contenu.description.toLowerCase().includes(this.currentFilter.search.toLowerCase());
            
            const matchesType = this.currentFilter.type === 'tous' || contenu.type === this.currentFilter.type;
            const matchesMatiere = this.currentFilter.matiere === 'toutes' || contenu.matiere === this.currentFilter.matiere;
            
            return matchesSearch && matchesType && matchesMatiere;
        });
        
        this.renderTable();
        this.updateCount();
    },
    
    renderTable() {
        const tbody = document.getElementById('contenus-table-body');
        if (!tbody) return;
        
        tbody.innerHTML = this.filteredData.map(contenu => `
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-4 px-4">
                    <div>
                        <p class="font-medium text-black">${contenu.titre}</p>
                        <p class="text-sm text-gray-600 line-clamp-2">${contenu.description}</p>
                        <p class="text-xs text-gray-500 mt-1">${contenu.taille} • ${formatDate(contenu.dateCreation)}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas ${this.getTypeIcon(contenu.type)} ${this.getTypeColor(contenu.type)}"></i>
                        <span class="text-sm capitalize">${contenu.type}</span>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div>
                        <p class="text-sm font-medium text-black">${contenu.matiere}</p>
                        <p class="text-sm text-gray-600">${contenu.niveau}</p>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <p class="text-sm text-black">${contenu.auteur}</p>
                </td>
                <td class="py-4 px-4">
                    <span class="px-2 py-1 text-xs rounded-full ${this.getStatutClass(contenu.statut)}">
                        ${contenu.statut}
                    </span>
                </td>
                <td class="py-4 px-4">
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-download text-gray-400"></i>
                        <span class="text-sm">${contenu.telechargements}</span>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div class="flex items-center space-x-2">
                        <button onclick="Contenus.showDetailModal(${contenu.id})" 
                                class="p-2 text-gray-600 hover:text-primary transition-colors" 
                                title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="Contenus.showEditModal(${contenu.id})" 
                                class="p-2 text-gray-600 hover:text-primary transition-colors" 
                                title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="Contenus.deleteContenu(${contenu.id})" 
                                class="p-2 text-red-600 hover:text-red-700 transition-colors" 
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    },
    
    updateCount() {
        const countElement = document.getElementById('count-contenus');
        if (countElement) {
            countElement.textContent = this.filteredData.length;
        }
    },
    
    getTypeIcon(type) {
        switch (type) {
            case 'pdf': return 'fa-file-pdf';
            case 'video': return 'fa-video';
            case 'image': return 'fa-image';
            default: return 'fa-file';
        }
    },
    
    getTypeColor(type) {
        switch (type) {
            case 'pdf': return 'text-red-600';
            case 'video': return 'text-blue-600';
            case 'image': return 'text-green-600';
            default: return 'text-gray-600';
        }
    },
    
    getStatutClass(statut) {
        switch (statut) {
            case 'publié': return 'bg-green-100 text-green-800';
            case 'brouillon': return 'bg-yellow-100 text-yellow-800';
            case 'archivé': return 'bg-gray-100 text-gray-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    },
    
    showDetailModal(id) {
        const contenu = this.data.find(c => c.id === id);
        if (!contenu) return;
        
        const modalContent = `
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-black">Détails du contenu</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Titre</label>
                            <p class="mt-1 text-sm text-black">${contenu.titre}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Type</label>
                            <p class="mt-1 text-sm text-black capitalize">${contenu.type}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Matière</label>
                            <p class="mt-1 text-sm text-black">${contenu.matiere}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Niveau</label>
                            <p class="mt-1 text-sm text-black">${contenu.niveau}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Auteur</label>
                            <p class="mt-1 text-sm text-black">${contenu.auteur}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Taille</label>
                            <p class="mt-1 text-sm text-black">${contenu.taille}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-1 text-sm text-black">${contenu.description}</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Fermer
                    </button>
                </div>
            </div>
        `;
        
        showModal(modalContent);
    },
    
    showAddModal() {
        const modalContent = `
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-black">Ajouter un nouveau contenu</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-contenu-form" class="space-y-4">
                    <div>
                        <label for="add-titre" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" id="add-titre" name="titre" placeholder="Titre du contenu" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div>
                        <label for="add-description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="add-description" name="description" placeholder="Description du contenu" rows="3" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="add-matiere" class="block text-sm font-medium text-gray-700">Matière</label>
                            <select id="add-matiere" name="matiere" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Sélectionner une matière</option>
                                <option value="Mathématiques">Mathématiques</option>
                                <option value="Français">Français</option>
                                <option value="Sciences">Sciences</option>
                                <option value="Anglais">Anglais</option>
                            </select>
                        </div>
                        <div>
                            <label for="add-niveau" class="block text-sm font-medium text-gray-700">Niveau</label>
                            <select id="add-niveau" name="niveau" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Sélectionner un niveau</option>
                                <option value="Primaire">Primaire</option>
                                <option value="Collège">Collège</option>
                                <option value="Lycée">Lycée</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="add-fichier" class="block text-sm font-medium text-gray-700">Fichier</label>
                        <div class="mt-2 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="fas fa-upload text-gray-400 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600">Cliquez pour sélectionner un fichier ou glissez-déposez</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, DOCX, MP4, JPG, PNG (max. 50MB)</p>
                            <input type="file" id="add-fichier" name="fichier" accept=".pdf,.docx,.mp4,.jpg,.jpeg,.png" class="hidden">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        `;
        
        showModal(modalContent);
        
        // Bind form submission and file upload
        document.getElementById('add-contenu-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleAddSubmit(e.target);
        });
        
        // File upload click handler
        document.querySelector('.border-dashed').addEventListener('click', () => {
            document.getElementById('add-fichier').click();
        });
    },
    
    showEditModal(id) {
        const contenu = this.data.find(c => c.id === id);
        if (!contenu) return;
        
        const modalContent = `
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-black">Modifier le contenu</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="edit-contenu-form" class="space-y-4">
                    <input type="hidden" name="contenu_id" value="${contenu.id}">
                    <div>
                        <label for="edit-titre" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" id="edit-titre" name="titre" value="${contenu.titre}" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div>
                        <label for="edit-description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="edit-description" name="description" rows="3" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">${contenu.description}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit-statut" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select id="edit-statut" name="statut" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="brouillon" ${contenu.statut === 'brouillon' ? 'selected' : ''}>Brouillon</option>
                                <option value="publié" ${contenu.statut === 'publié' ? 'selected' : ''}>Publié</option>
                                <option value="archivé" ${contenu.statut === 'archivé' ? 'selected' : ''}>Archivé</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit-niveau" class="block text-sm font-medium text-gray-700">Niveau</label>
                            <select id="edit-niveau" name="niveau" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="Primaire" ${contenu.niveau === 'Primaire' ? 'selected' : ''}>Primaire</option>
                                <option value="Collège" ${contenu.niveau === 'Collège' ? 'selected' : ''}>Collège</option>
                                <option value="Lycée" ${contenu.niveau === 'Lycée' ? 'selected' : ''}>Lycée</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        `;
        
        showModal(modalContent);
        
        // Bind form submission
        document.getElementById('edit-contenu-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleEditSubmit(e.target);
        });
    },
    
    handleAddSubmit(form) {
        const formData = new FormData(form);
        
        // This would be sent to PHP endpoint: admin/contenus/create.php
        submitForm(Object.fromEntries(formData), 'admin/contenus/create.php', () => {
            // Add to local data (in real app, would get ID from server response)
            const newContenu = {
                id: this.data.length + 1,
                titre: formData.get('titre'),
                description: formData.get('description'),
                type: 'pdf', // Would be determined by file upload
                matiere: formData.get('matiere'),
                niveau: formData.get('niveau'),
                taille: '1.0 MB', // Would be calculated from uploaded file
                dateCreation: new Date().toISOString().split('T')[0],
                auteur: 'Admin',
                telechargements: 0,
                statut: 'brouillon'
            };
            this.data.push(newContenu);
            this.applyFilters();
            this.loadStats();
            closeModal();
        });
    },
    
    handleEditSubmit(form) {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        // This would be sent to PHP endpoint: admin/contenus/update.php
        submitForm(data, 'admin/contenus/update.php', () => {
            const index = this.data.findIndex(c => c.id === parseInt(data.contenu_id));
            if (index !== -1) {
                this.data[index] = { ...this.data[index], ...data };
                this.applyFilters();
                this.loadStats();
            }
            closeModal();
        });
    },
    
    deleteContenu(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce contenu ? Cette action est irréversible.')) {
            const formData = { contenu_id: id };
            
            // This would be sent to PHP endpoint: admin/contenus/delete.php
            submitForm(formData, 'admin/contenus/delete.php', () => {
                this.data = this.data.filter(c => c.id !== id);
                this.applyFilters();
                this.loadStats();
            });
        }
    }
};