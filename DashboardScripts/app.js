// Main app functionality
const App = {
    currentSection: 'dashboard',
    
    init() {
        this.initNavigation();
        this.loadSection('dashboard');
    },
    
    initNavigation() {
        // Set up navigation event listeners
        document.addEventListener('DOMContentLoaded', () => {
            this.init();
        });
    },
    
    loadSection(sectionName) {
        // Hide all sections
        document.querySelectorAll('.section').forEach(section => {
            section.classList.add('hidden');
            section.classList.remove('active');
        });
        
        // Show target section
        const targetSection = document.getElementById(`${sectionName}-section`);
        if (targetSection) {
            targetSection.classList.remove('hidden');
            targetSection.classList.add('active');
        }
        
        // Update navigation
        this.updateNavigation(sectionName);
        
        // Update page title
        this.updatePageTitle(sectionName);
        
        // Load section content
        this.loadSectionContent(sectionName);
        
        this.currentSection = sectionName;
    },
    
    updateNavigation(activeSection) {
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('bg-primary', 'text-white');
            item.classList.add('text-gray-700', 'hover:bg-gray-100');
        });
        
        const activeItem = document.querySelector(`[data-section="${activeSection}"]`);
        if (activeItem) {
            activeItem.classList.add('bg-primary', 'text-white');
            activeItem.classList.remove('text-gray-700', 'hover:bg-gray-100');
        }
    },
    
    updatePageTitle(section) {
        const titles = {
            dashboard: 'Tableau de bord administrateur',
            repetiteurs: 'Gestion des Répétiteurs',
            parents: 'Gestion des Parents/Élèves',
            abonnements: 'Gestion des Abonnements',
            contenus: 'Gestion des Contenus Éducatifs',
            avis: 'Gestion des Avis et Modération',
            parametres: 'Paramètres du Système'
        };
        
        const titleElement = document.getElementById('page-title');
        if (titleElement && titles[section]) {
            titleElement.textContent = titles[section];
        }
    },
    
    loadSectionContent(section) {
        switch(section) {
            case 'dashboard':
                Dashboard.init();
                break;
            case 'repetiteurs':
                Repetiteurs.init();
                break;
            case 'parents':
                Parents.init();
                break;
            case 'abonnements':
                Abonnements.init();
                break;
            case 'contenus':
                Contenus.init();
                break;
            case 'avis':
                Avis.init();
                break;
            case 'parametres':
                Parametres.init();
                break;
        }
    }
};

// Global navigation function
function showSection(sectionName) {
    App.loadSection(sectionName);
}

// Modal functions
function showModal(content) {
    const modalOverlay = document.getElementById('modal-overlay');
    const modalContent = document.getElementById('modal-content');
    
    modalContent.innerHTML = content;
    modalOverlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modalOverlay = document.getElementById('modal-overlay');
    modalOverlay.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Utility functions
function formatDate(dateString) {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Form submission handler ready for PHP integration
function submitForm(formData, endpoint, successCallback) {
    const mapEndpointToAction = (url) => {
        if (url.includes('avis/update-status')) return { action: 'update_avis_statut', map: (d) => ({ id: d.avis_id, statut: d.statut }) };
        if (url.includes('repetiteurs/valider')) return { action: 'update_repetiteur_statut', map: (d) => ({ id: d.demande_id, statut: 'actif' }) };
        if (url.includes('repetiteurs/rejeter')) return { action: 'update_repetiteur_statut', map: (d) => ({ id: d.demande_id, statut: 'rejete' }) };
        if (url.includes('repetiteurs/complements')) return { action: 'noop', map: (d) => d };
        if (url.includes('contenus/create')) return { action: 'noop', map: (d) => d };
        if (url.includes('contenus/update')) return { action: 'noop', map: (d) => d };
        if (url.includes('contenus/delete')) return { action: 'noop', map: (d) => d };
        if (url.includes('parametres/update-general')) return { action: 'noop', map: (d) => d };
        return { action: 'noop', map: (d) => d };
    };

    try {
        const { action, map } = mapEndpointToAction(endpoint || '');
        const payload = map(formData || {});
        if (action === 'noop') {
            // Pas d’action serveur encore – succès immédiat
            if (successCallback) successCallback({});
            showToast('Opération réussie');
            return;
        }
        fetch(`admin_api.php?action=${encodeURIComponent(action)}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.success) {
                if (successCallback) successCallback(data);
                showToast(data.message || 'Opération réussie');
            } else {
                showToast((data && data.message) || 'Erreur lors de l\'opération', 'error');
            }
        })
        .catch(err => {
            console.error('Erreur requête:', err);
            showToast('Erreur de connexion', 'error');
        });
    } catch (e) {
        console.error(e);
        showToast('Erreur interne', 'error');
    }
}

// Helpers API simples
function apiGet(action, params = {}) {
    const qs = new URLSearchParams({ action, ...params }).toString();
    return fetch(`admin_api.php?${qs}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json());
}

function apiPost(action, data = {}) {
    return fetch(`admin_api.php?action=${encodeURIComponent(action)}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(data)
    }).then(r => r.json());
}

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    App.init();
});