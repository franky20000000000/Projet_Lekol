// Dashboard functionality
const Dashboard = {
    charts: {},
    
    init() {
        this.loadActivitiesRecentes();
        this.initCharts();
        // Charger stats réelles si éléments présents
        apiGet('get_stats').then(data => {
            if (data && data.success && data.data) {
                const d = data.data;
                const elRep = document.querySelector('#dashboard-section [data-stat="repetiteurs"]');
                const elPar = document.querySelector('#dashboard-section [data-stat="parents"]');
                const elAvis = document.querySelector('#dashboard-section [data-stat="avis"]');
                // Si vous ajoutez des attributs data-stat dans le HTML, ils seront mis à jour ici
                if (elRep) elRep.textContent = d.repetiteurs;
                if (elPar) elPar.textContent = d.parents;
                if (elAvis) elAvis.textContent = d.avis;
            }
        }).catch(()=>{});
    },
    
    loadActivitiesRecentes() {
        const container = document.getElementById('activites-recentes');
        if (!container) return;
        container.innerHTML = '<div class="text-sm text-gray-500">Chargement...</div>';
        apiGet('get_recent_activities').then(res => {
            if (!(res && res.success)) { container.innerHTML = ''; return; }
            const items = (res.data && res.data.items) || [];
            if (items.length === 0) { container.innerHTML = '<div class="text-sm text-gray-500">Aucune activité récente</div>'; return; }
            container.innerHTML = items.map(activity => `
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 ${Dashboard.getTypeColor(activity.type)} rounded-full"></div>
                        <div>
                            <p class="font-medium text-black">${activity.name || ''}</p>
                            <p class="text-sm text-gray-600">${activity.action || ''}</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">${Dashboard.formatRelative(activity.timestamp)}</span>
                </div>
            `).join('');
        }).catch(() => { container.innerHTML = ''; });
    },
    
    getTypeColor(type) {
        switch (type) {
            case 'inscription_repetiteur': return 'bg-blue-500';
            case 'inscription_parent': return 'bg-green-500';
            case 'avis': return 'bg-yellow-500';
            default: return 'bg-primary';
        }
    },
    
    formatRelative(ts) {
        try {
            const d = new Date(ts);
            const diff = (Date.now() - d.getTime()) / 1000;
            if (diff < 60) return "À l'instant";
            if (diff < 3600) return `Il y a ${Math.floor(diff/60)} min`;
            if (diff < 86400) return `Il y a ${Math.floor(diff/3600)} h`;
            return d.toLocaleDateString('fr-FR');
        } catch { return ''; }
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