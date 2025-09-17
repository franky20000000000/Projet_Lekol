// Fonctionnalité de gestion des paramètres
const Parametres = {
    // Paramètres généraux
    parametresGeneraux: {
        nomPlateforme: 'LEKOL',
        emailContact: 'contact@lekol.com',
        telephoneContact: '+237 694210071',
        adresse: 'Douala, Kotto',
    },
    
    // Initialisation du composant
    init() {
        this.render();
        this.bindEvents();
    },
    
    // Rendu de l'interface
    render() {
        const section = document.getElementById('parametres-section');
        section.innerHTML = `
            <div class="space-y-6">
                <!-- En-tête -->
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-black">Paramètres du Système</h2>
                    <a href="Deconnexion.php">
                    <button onclick="Parametres.deconnexion()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Déconnexion
                    </button>
                    </a>
                </div>

                <!-- Paramètres généraux -->
                <div class="space-y-6">
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-black">Informations générales</h3>
                        </div>
                        <div class="p-6">
                            <form id="general-form" class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="nom-plateforme" class="block text-sm font-medium text-gray-700">Nom de la plateforme</label>
                                        <input type="text" id="nom-plateforme" name="nomPlateforme" value="${this.parametresGeneraux.nomPlateforme}"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label for="email-contact" class="block text-sm font-medium text-gray-700">Email de contact</label>
                                        <input type="email" id="email-contact" name="emailContact" value="${this.parametresGeneraux.emailContact}"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label for="telephone-contact" class="block text-sm font-medium text-gray-700">Téléphone de contact</label>
                                        <input type="tel" id="telephone-contact" name="telephoneContact" value="${this.parametresGeneraux.telephoneContact}"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>
                                <div>
                                    <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                                    <input type="text" id="adresse" name="adresse" value="${this.parametresGeneraux.adresse}"
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button onclick="Parametres.sauvegarderGeneral()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Enregistrer les paramètres
                        </button>
                    </div>
                </div>
            </div>
        `;
    },
    
    // Liaison des événements
    bindEvents() {
        // Les soumissions de formulaire seront gérées lors du rendu du contenu
    },
    
    // Sauvegarder les paramètres généraux
    sauvegarderGeneral() {
        const formulaire = document.getElementById('general-form');
        const donneesFormulaire = new FormData(formulaire);
        
        const donnees = {
            ...Object.fromEntries(donneesFormulaire),
            dureeEssaiGratuit: document.getElementById('duree-essai').value
        };
        
        // Ceci serait envoyé au endpoint PHP: admin/parametres/update-general.php
        this.soumettreFormulaire(donnees, 'admin/parametres/update-general.php', () => {
            this.parametresGeneraux = { ...this.parametresGeneraux, ...donnees };
            alert('Paramètres enregistrés avec succès !');
        });
    },
    
    // Déconnexion de l'administrateur
    deconnexion() {
        if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
            // Redirection vers le script de déconnexion PHP
            window.location.href = 'admin/deconnexion.php';
        }
    },
    
    // Fonction utilitaire pour soumettre un formulaire
    soumettreFormulaire(donnees, url, callback) {
        console.log('Soumission des données:', donnees, 'vers:', url);
        // Simulation d'une requête AJAX
        setTimeout(() => {
            if (callback) callback();
        }, 500);
    }
};

// Initialisation lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    Parametres.init();
});