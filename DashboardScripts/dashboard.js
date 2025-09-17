// Dashboard functionality
const Dashboard = {
    charts: {},
    
    init() {
        this.loadActivitiesRecentes();
        this.initCharts();
    },
    
    loadActivitiesRecentes() {
        const activitiesData = [
            { type: 'inscription', name: 'Marie Dubois', action: 'Nouveau répétiteur inscrit', time: 'Il y a 2h' },
            { type: 'avis', name: 'Jean Martin', action: 'Nouvel avis 5 étoiles', time: 'Il y a 3h' },
            { type: 'contact', name: 'Sophie Bernard', action: 'Contact avec répétiteur', time: 'Il y a 5h' },
            { type: 'abonnement', name: 'Pierre Durand', action: 'Abonnement renouvelé', time: 'Il y a 1 jour' },
        ];
        
        const container = document.getElementById('activites-recentes');
        if (!container) return;
        
        container.innerHTML = activitiesData.map(activity => `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-primary rounded-full"></div>
                    <div>
                        <p class="font-medium text-black">${activity.name}</p>
                        <p class="text-sm text-gray-600">${activity.action}</p>
                    </div>
                </div>
                <span class="text-sm text-gray-500">${activity.time}</span>
            </div>
        `).join('');
    },
    
    initCharts() {
        this.initInscriptionsChart();
        this.initMatieresChart();
    },
    
    initInscriptionsChart() {
        const ctx = document.getElementById('inscriptionsChart');
        if (!ctx) return;
        
        // Destroy existing chart if it exists
        if (this.charts.inscriptions) {
            this.charts.inscriptions.destroy();
        }
        
        const data = {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [
                {
                    label: 'Répétiteurs',
                    data: [12, 19, 15, 22, 28, 31],
                    borderColor: '#2B80F6',
                    backgroundColor: 'rgba(43, 128, 246, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Parents',
                    data: [45, 52, 48, 61, 73, 86],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                }
            ]
        };
        
        this.charts.inscriptions = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    },
    
    initMatieresChart() {
        const ctx = document.getElementById('matieresChart');
        if (!ctx) return;
        
        // Destroy existing chart if it exists
        if (this.charts.matieres) {
            this.charts.matieres.destroy();
        }
        
        const data = {
            labels: ['Mathématiques', 'Français', 'Anglais', 'Sciences', 'Autres'],
            datasets: [{
                data: [35, 25, 20, 15, 5],
                backgroundColor: [
                    '#2B80F6',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444',
                    '#8B5CF6'
                ],
                borderWidth: 0
            }]
        };
        
        this.charts.matieres = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }
};