// Avis management functionality
const Avis = {
    data: [],
    
    filteredData: [],
    currentFilter: {
        search: '',
        statut: 'tous',
        note: 'toutes'
    },
    
    init() {
        this.render();
        this.bindEvents();
        this.fetch();
    },

    fetch() {
        apiGet('list_avis').then(res => {
            if (res && res.success) {
                const items = (res.data && res.data.items) || [];
                // Mapper vers le format attendu par le rendu
                this.data = items.map(a => ({
                    id: a.id,
                    repetiteur: `${a.rep_prenom || ''} ${a.rep_nom || ''}`.trim() || `Répétiteur #${a.repetiteur_id}`,
                    parent: `${a.parent_prenom || ''} ${a.parent_nom || ''}`.trim() || `Parent #${a.parent_id}`,
                    note: a.note,
                    commentaire: a.commentaire,
                    date: a.date_creation,
                    statut: a.statut === 'approuve' ? 'approuvé' : (a.statut || 'en_attente'),
                    matiere: ''
                }));
                this.filteredData = [...this.data];
                this.renderAvis();
                this.loadStats();
            }
        }).catch(()=>{});
    },
    
    render() {
        const section = document.getElementById('avis-section');
        section.innerHTML = `
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-black">Gestion des Avis et Modération</h2>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Total Avis</p>
                                <p class="text-2xl font-bold text-black" id="stat-total-avis">${this.data.length}</p>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-comments text-xl text-primary"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Note Moyenne</p>
                                <div class="flex items-center space-x-2">
                                    <p class="text-2xl font-bold text-black" id="stat-note-moyenne">0</p>
                                    <i class="fas fa-star text-yellow-500"></i>
                                </div>
                            </div>
                            <div class="p-3 bg-yellow-50 rounded-lg">
                                <i class="fas fa-star text-xl text-yellow-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">En Attente</p>
                                <p class="text-2xl font-bold text-black" id="stat-en-attente">0</p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded-lg">
                                <i class="fas fa-clock text-xl text-orange-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Signalés</p>
                                <p class="text-2xl font-bold text-black" id="stat-signales">0</p>
                            </div>
                            <div class="p-3 bg-red-50 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-xl text-red-600"></i>
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
                                <input type="text" id="search-avis" placeholder="Rechercher par répétiteur, parent ou commentaire..." 
                                       class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select id="filter-statut-avis" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="tous">Tous les statuts</option>
                                <option value="approuvé">Approuvé</option>
                                <option value="en_attente">En attente</option>
                                <option value="signalé">Signalé</option>
                                <option value="masqué">Masqué</option>
                            </select>
                            <select id="filter-note-avis" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="toutes">Toutes notes</option>
                                <option value="5">5 étoiles</option>
                                <option value="4">4 étoiles</option>
                                <option value="3">3 étoiles</option>
                                <option value="2">2 étoiles</option>
                                <option value="1">1 étoile</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Avis List -->
                <div class="bg-white border border-gray-200 rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-black">Liste des avis (<span id="count-avis">${this.data.length}</span>)</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4" id="avis-list">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        `;
    },
    
    bindEvents() {
        // Search functionality
        document.addEventListener('input', (e) => {
            if (e.target.id === 'search-avis') {
                this.currentFilter.search = e.target.value;
                this.applyFilters();
            }
        });
        
        // Filter functionality
        document.addEventListener('change', (e) => {
            if (e.target.id === 'filter-statut-avis') {
                this.currentFilter.statut = e.target.value;
                this.applyFilters();
            } else if (e.target.id === 'filter-note-avis') {
                this.currentFilter.note = e.target.value;
                this.applyFilters();
            }
        });
    },
    
    loadStats() {
        if (this.data.length === 0) {
            document.getElementById('stat-note-moyenne').textContent = '0.0';
            document.getElementById('stat-en-attente').textContent = '0';
            document.getElementById('stat-signales').textContent = '0';
            return;
        }
        const noteMoyenne = this.data.reduce((sum, avis) => sum + (parseInt(avis.note, 10) || 0), 0) / this.data.length;
        const enAttente = this.data.filter(a => a.statut === 'en_attente').length;
        const signales = this.data.filter(a => a.statut === 'signalé').length;
        document.getElementById('stat-note-moyenne').textContent = noteMoyenne.toFixed(1);
        document.getElementById('stat-en-attente').textContent = enAttente;
        document.getElementById('stat-signales').textContent = signales;
    },
    
    applyFilters() {
        this.filteredData = this.data.filter(avis => {
            const matchesSearch = this.currentFilter.search === '' || 
                avis.repetiteur.toLowerCase().includes(this.currentFilter.search.toLowerCase()) ||
                avis.parent.toLowerCase().includes(this.currentFilter.search.toLowerCase()) ||
                avis.commentaire.toLowerCase().includes(this.currentFilter.search.toLowerCase());
            
            const matchesStatut = this.currentFilter.statut === 'tous' || avis.statut === this.currentFilter.statut;
            const matchesNote = this.currentFilter.note === 'toutes' || avis.note.toString() === this.currentFilter.note;
            
            return matchesSearch && matchesStatut && matchesNote;
        });
        
        this.renderAvis();
        this.updateCount();
    },
    
    renderAvis() {
        const container = document.getElementById('avis-list');
        if (!container) return;
        
        container.innerHTML = this.filteredData.map(avis => `
            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center space-x-4">
                        <div>
                            <p class="font-medium text-black">${avis.repetiteur}</p>
                            <p class="text-sm text-gray-600">${avis.matiere}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            ${this.renderStars(avis.note)}
                            <span class="text-sm font-medium">${avis.note}/5</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs rounded-full ${this.getStatutClass(avis.statut)}">
                            ${this.getStatutLabel(avis.statut)}
                        </span>
                        <span class="text-sm text-gray-500">${formatDate(avis.date)}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-1">Par ${avis.parent}</p>
                    <p class="text-sm text-black">${avis.commentaire}</p>
                </div>

                ${avis.reponseRepetiteur ? `
                    <div class="bg-blue-50 p-3 rounded-lg mb-3">
                        <p class="text-sm font-medium text-blue-900 mb-1">Réponse du répétiteur :</p>
                        <p class="text-sm text-blue-800">${avis.reponseRepetiteur}</p>
                    </div>
                ` : ''}

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <button onclick="Avis.showDetailModal(${avis.id})" 
                                class="px-3 py-1 text-sm text-gray-600 hover:text-primary transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            Détails
                        </button>
                        <button onclick="Avis.showResponseModal(${avis.id})" 
                                class="px-3 py-1 text-sm text-gray-600 hover:text-primary transition-colors">
                            <i class="fas fa-reply mr-1"></i>
                            Répondre
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button onclick="Avis.approveAvis(${avis.id})" 
                                class="p-2 text-green-600 hover:text-green-700 transition-colors" 
                                title="Approuver">
                            <i class="fas fa-check-circle"></i>
                        </button>
                        <button onclick="Avis.hideAvis(${avis.id})" 
                                class="p-2 text-red-600 hover:text-red-700 transition-colors" 
                                title="Masquer">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    },
    
    updateCount() {
        const countElement = document.getElementById('count-avis');
        if (countElement) {
            countElement.textContent = this.filteredData.length;
        }
    },
    
    renderStars(note) {
        return Array.from({length: 5}, (_, i) => 
            `<i class="fas fa-star ${i < note ? 'text-yellow-500' : 'text-gray-300'}"></i>`
        ).join('');
    },
    
    getStatutClass(statut) {
        switch (statut) {
            case 'approuvé': return 'bg-green-100 text-green-800';
            case 'en_attente': return 'bg-yellow-100 text-yellow-800';
            case 'signalé': return 'bg-red-100 text-red-800';
            case 'masqué': return 'bg-gray-100 text-gray-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    },
    
    getStatutLabel(statut) {
        switch (statut) {
            case 'en_attente': return 'En attente';
            default: return statut;
        }
    },
    
    showDetailModal(id) {
        const avis = this.data.find(a => a.id === id);
        if (!avis) return;
        
        const modalContent = `
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-black">Détails de l'avis</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Répétiteur</label>
                            <p class="mt-1 text-sm text-black">${avis.repetiteur}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Parent</label>
                            <p class="mt-1 text-sm text-black">${avis.parent}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Matière</label>
                            <p class="mt-1 text-sm text-black">${avis.matiere}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Date</label>
                            <p class="mt-1 text-sm text-black">${formatDate(avis.date)}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Note</label>
                            <div class="mt-1 flex items-center space-x-2">
                                ${this.renderStars(avis.note)}
                                <span class="text-sm font-medium">${avis.note}/5</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Statut</label>
                            <span class="mt-1 px-2 py-1 text-xs rounded-full ${this.getStatutClass(avis.statut)}">
                                ${this.getStatutLabel(avis.statut)}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Commentaire</label>
                        <p class="mt-1 text-sm text-black">${avis.commentaire}</p>
                    </div>
                    ${avis.reponseRepetiteur ? `
                        <div>
                            <label class="text-sm font-medium text-gray-700">Réponse du répétiteur</label>
                            <p class="mt-1 text-sm text-black">${avis.reponseRepetiteur}</p>
                        </div>
                    ` : ''}
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
    
    showResponseModal(id) {
        const avis = this.data.find(a => a.id === id);
        if (!avis) return;
        
        const modalContent = `
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-black">Répondre à l'avis</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-black mb-2">Avis de ${avis.parent} :</p>
                        <p class="text-sm text-gray-700">${avis.commentaire}</p>
                    </div>
                    <form id="response-form">
                        <input type="hidden" name="avis_id" value="${avis.id}">
                        <div>
                            <label for="reponse" class="block text-sm font-medium text-gray-700">Votre réponse (au nom de l'administrateur)</label>
                            <textarea id="reponse" name="reponse" placeholder="Tapez votre réponse ici..." rows="4" required
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">${avis.reponseRepetiteur || ''}</textarea>
                        </div>
                        <div class="flex justify-end space-x-2 mt-4">
                            <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                                Annuler
                            </button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                Envoyer la réponse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;
        
        showModal(modalContent);
        
        // Bind form submission
        document.getElementById('response-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleResponseSubmit(e.target);
        });
    },
    
    handleResponseSubmit(form) {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        // This would be sent to PHP endpoint: admin/avis/respond.php
        submitForm(data, 'admin/avis/respond.php', () => {
            const index = this.data.findIndex(a => a.id === parseInt(data.avis_id));
            if (index !== -1) {
                this.data[index].reponseRepetiteur = data.reponse;
                this.applyFilters();
            }
            closeModal();
        });
    },
    
    approveAvis(id) {
        const payload = { id, statut: 'approuve' };
        apiPost('update_avis_statut', payload).then(res => {
            if (res && res.success) {
                const i = this.data.findIndex(a => a.id === id);
                if (i !== -1) this.data[i].statut = 'approuvé';
                this.applyFilters();
                this.loadStats();
                showToast('Avis approuvé');
            } else {
                showToast((res && res.message) || 'Erreur', 'error');
            }
        }).catch(()=> showToast('Erreur connexion', 'error'));
    },
    
    hideAvis(id) {
        if (!confirm('Masquer cet avis ?')) return;
        const payload = { id, statut: 'rejete' };
        apiPost('update_avis_statut', payload).then(res => {
            if (res && res.success) {
                const i = this.data.findIndex(a => a.id === id);
                if (i !== -1) this.data[i].statut = 'masqué';
                this.applyFilters();
                this.loadStats();
                showToast('Avis masqué');
            } else {
                showToast((res && res.message) || 'Erreur', 'error');
            }
        }).catch(()=> showToast('Erreur connexion', 'error'));
    }
};