<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: AdminLogin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Dashboard.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-poppins">
    <!-- Menu mobile -->
    <div class="lg:hidden fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50 p-4 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <p class="text-[#2B80F6] font-bold text-3xl">Lékol</p>
        </div>
        <button id="mobile-menu-button" class="p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <div class="h-screen flex pt-16 lg:pt-0">
        <!-- Sidebar - Version mobile cachée par défaut -->
        <aside class="w-64 bg-white border-r border-gray-200 h-full flex-col fixed lg:relative inset-y-0 left-0 z-40 transform -translate-x-full lg:translate-x-0 transition duration-200 ease-in-out" id="sidebar">
            <!-- Fermer le menu en mobile -->
            <div class="p-4 border-b border-gray-200 flex justify-between items-center lg:hidden">
                <div class="flex items-center space-x-2">
                    <p class="text-[#2B80F6] font-bold text-3xl">Lékol</p>
                </div>
                <button id="close-sidebar" class="p-2 rounded-md text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Logo (version desktop) -->
            <div class="p-6 border-b border-gray-200 hidden lg:block">
                <div class="flex items-center space-x-2">
                    <p class="text-[#2B80F6] font-bold text-3xl">Lékol</p>
                </div>
                <p class="text-sm text-gray-600 mt-1">Admin Dashboard</p>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 lg:p-6 overflow-y-auto">
                <ul class="space-y-2" id="nav-menu">
                    <li>
                        <button onclick="showSection('dashboard')" 
                                class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors text-left bg-primary text-white" 
                                data-section="dashboard">
                            <i class="fas fa-home w-5"></i>
                            <span>Tableau de bord</span>
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('repetiteurs')" 
                                class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors text-left text-gray-700 hover:bg-gray-100" 
                                data-section="repetiteurs">
                            <i class="fas fa-user-tie w-5"></i>
                            <span>Répétiteurs</span>
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('parents')" 
                                class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors text-left text-gray-700 hover:bg-gray-100" 
                                data-section="parents">
                            <i class="fas fa-users w-5"></i>
                            <span>Parents</span>
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('abonnements')" 
                                class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors text-left text-gray-700 hover:bg-gray-100" 
                                data-section="abonnements">
                            <i class="fas fa-credit-card w-5"></i>
                            <span>Abonnements</span>
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('contenus')" 
                                class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors text-left text-gray-700 hover:bg-gray-100" 
                                data-section="contenus">
                            <i class="fas fa-book-open w-5"></i>
                            <span>Contenus</span>
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('avis')" 
                                class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors text-left text-gray-700 hover:bg-gray-100" 
                                data-section="avis">
                            <i class="fas fa-comments w-5"></i>
                            <span>Avis & Modération</span>
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('parametres')" 
                                class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors text-left text-gray-700 hover:bg-gray-100" 
                                data-section="parametres">
                            <i class="fas fa-cog w-5"></i>
                            <span>Paramètres</span>
                        </button>
                    </li>
                </ul>
            </nav>

            <!-- Footer -->
            <div class="p-4 lg:p-6 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-medium">A</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-black">Admin</p>
                        <p class="text-xs text-gray-600">tiomenecabrel@gmail.com</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay pour mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden" onclick="closeSidebar()"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Navbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-6">
                <div class="flex items-center space-x-4">
                    <h1 class="text-lg lg:text-xl font-semibold text-black" id="page-title">Tableau de bord administrateur</h1>
                </div>

                <div class="flex items-center space-x-2 lg:space-x-4">
                    <!-- Search - caché sur mobile -->
                    <div class="relative hidden md:block">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Rechercher..." 
                               class="pl-10 pr-4 py-2 w-40 lg:w-64 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <!-- Icône recherche pour mobile -->
                    <button class="md:hidden p-2 text-gray-600 hover:text-primary transition-colors">
                        <i class="fas fa-search text-lg"></i>
                    </button>

                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-600 hover:text-primary transition-colors">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="container mx-auto">
                    <!-- Dashboard Overview Section -->
                    <div id="dashboard-section" class="section active">
                        <div class="space-y-6">
                            <!-- Stats Cards - Grille responsive -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                                <div class="bg-white border border-gray-200 rounded-lg p-4 lg:p-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs lg:text-sm text-gray-600 mb-2">Répétiteurs inscrits</p>
                                            <p class="text-xl lg:text-2xl font-bold text-black" data-stat="repetiteurs">0</p>
                                        </div>
                                        <div class="p-2 lg:p-3 bg-blue-50 rounded-lg">
                                            <i class="fas fa-user-tie text-lg lg:text-xl text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white border border-gray-200 rounded-lg p-4 lg:p-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs lg:text-sm text-gray-600 mb-2">Parents</p>
                                            <p class="text-xl lg:text-2xl font-bold text-black" data-stat="parents">0</p>
                                        </div>
                                        <div class="p-2 lg:p-3 bg-green-50 rounded-lg">
                                            <i class="fas fa-users text-lg lg:text-xl text-green-600"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white border border-gray-200 rounded-lg p-4 lg:p-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs lg:text-sm text-gray-600 mb-2">Abonnements actifs</p>
                                            <p class="text-xl lg:text-2xl font-bold text-black">0</p>
                                        </div>
                                        <div class="p-2 lg:p-3 bg-purple-50 rounded-lg">
                                            <i class="fas fa-credit-card text-lg lg:text-xl text-purple-600"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white border border-gray-200 rounded-lg p-4 lg:p-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs lg:text-sm text-gray-600 mb-2">Contacts initiés</p>
                                            <p class="text-xl lg:text-2xl font-bold text-black" data-stat="avis">0</p>
                                        </div>
                                        <div class="p-2 lg:p-3 bg-orange-50 rounded-lg">
                                            <i class="fas fa-phone text-lg lg:text-xl text-orange-600"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Activités récentes -->
                            <div class="bg-white border border-gray-200 rounded-lg">
                                <div class="p-4 lg:p-6 border-b border-gray-200">
                                    <h3 class="text-base lg:text-lg font-semibold text-black">Activités récentes</h3>
                                </div>
                                <div class="p-4 lg:p-6">
                                    <div class="space-y-4" id="activites-recentes">
                                        <!-- Activities will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other sections will be here -->
                    <div id="repetiteurs-section" class="section hidden">
                        <!-- Content will be loaded here -->
                    </div>

                    <div id="parents-section" class="section hidden">
                        <!-- Content will be loaded here -->
                    </div>

                    <div id="abonnements-section" class="section hidden">
                        <!-- Content will be loaded here -->
                    </div>

                    <div id="contenus-section" class="section hidden">
                        <!-- Content will be loaded here -->
                    </div>

                    <div id="avis-section" class="section hidden">
                        <!-- Content will be loaded here -->
                    </div>

                    <div id="parametres-section" class="section hidden">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modals -->
    <div id="modal-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div id="modal-content" class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto" onclick="event.stopPropagation()">
                <!-- Modal content will be injected here -->
            </div>
        </div>
    </div>

    <script>
        // Gestion du menu responsive
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeSidebarButton = document.getElementById('close-sidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }

        mobileMenuButton.addEventListener('click', openSidebar);
        closeSidebarButton.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Redimensionnement de la fenêtre
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>

    <script src="DashboardScripts/app.js"></script>
    <script src="DashboardScripts/dashboard.js"></script>
    <script src="DashboardScripts/repetiteuRS.js"></script>
    <script src="DashboardScripts/parents.js"></script>
    <script src="DashboardScripts/Abonnements.js"></script>
    <script src="DashboardScripts/contenus.js"></script>
    <script src="DashboardScripts/avis.js"></script>
    <script src="DashboardScripts/parametres.js"></script>
    <script src="Scripts/tailwindcss.js"></script>
    <script src="Scripts/script.js"></script>
</body>
</html>