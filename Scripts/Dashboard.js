      tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2B80F6',
                    }
                }
            }
        }
      
      // Navigation entre les pages
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation des graphiques
            initCharts();
            
            // Gestion de la navigation
            const navItems = document.querySelectorAll('.nav-item');
            const pageContents = document.querySelectorAll('.page-content');
            const pageTitle = document.getElementById('pageTitle');
            
            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetPage = this.getAttribute('data-page');
                    
                    // Masquer toutes les pages
                    pageContents.forEach(page => {
                        page.classList.remove('active');
                    });
                    
                    // Afficher la page cible
                    document.getElementById(targetPage).classList.add('active');
                    
                    // Mettre à jour le titre de la page
                    pageTitle.textContent = this.textContent.trim();
                    
                    // Fermer le sidebar sur mobile
                    if (window.innerWidth < 768) {
                        document.getElementById('sidebar').classList.remove('open');
                        document.getElementById('overlay').classList.remove('open');
                    }
                });
            });
            
            // Toggle sidebar sur mobile
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('open');
                document.getElementById('overlay').classList.toggle('open');
            });
            
            // Fermer le sidebar en cliquant sur l'overlay
            document.getElementById('overlay').addEventListener('click', function() {
                document.getElementById('sidebar').classList.remove('open');
                this.classList.remove('open');
            });
        });
        
        // Initialisation des graphiques
        function initCharts() {
            // Graphique d'évolution des inscriptions
            const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
            const registrationsChart = new Chart(registrationsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    datasets: [
                        {
                            label: 'Répétiteurs',
                            data: [15, 25, 20, 30, 40, 35, 45, 50, 45, 55, 60, 65],
                            borderColor: '#2B80F6',
                            backgroundColor: 'rgba(43, 128, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Parents/Élèves',
                            data: [40, 50, 65, 70, 85, 90, 100, 110, 105, 115, 120, 125],
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
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
            
            // Graphique des matières les plus demandées
            const subjectsCtx = document.getElementById('subjectsChart').getContext('2d');
            const subjectsChart = new Chart(subjectsCtx, {
                type: 'bar',
                data: {
                    labels: ['Maths', 'Physique', 'Français', 'Anglais', 'SVT', 'Chimie'],
                    datasets: [{
                        label: 'Nombre de demandes',
                        data: [65, 59, 50, 48, 45, 40],
                        backgroundColor: [
                            'rgba(43, 128, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                            'rgba(236, 72, 153, 0.7)',
                            'rgba(59, 130, 246, 0.7)'
                        ],
                        borderColor: [
                            '#2B80F6',
                            '#10B981',
                            '#F59E0B',
                            '#8B5CF6',
                            '#EC4899',
                            '#3B82F6'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }