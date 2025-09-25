<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lekol";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Vérifier si l’utilisateur est connecté
if(!isset($_SESSION['id'])){
    header("Location: Connexion.php"); // Redirection si pas connecté
    exit;
}

// Récupérer les infos du parent connecté
$stmt = $conn->prepare("SELECT * FROM parent WHERE id = :id");
$stmt->execute(['id' => $_SESSION['id']]);
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$parent){
    echo "Erreur : utilisateur introuvable.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2B80F6">
    <title>Profil Parent</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-50 min-h-screen text-gray-800">
    <!-- Header -->
    <header id="navbar" class="fixed inset-x-0 top-0 z-50 backdrop-blur bg-white/70 border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="index2.php" class="flex items-center gap-2 group" aria-label="Retour à l’accueil">
                <span class="text-[#2B80F6] font-extrabold text-2xl tracking-tight group-hover:opacity-90">Lékol</span>
            </a>
            <div class="flex items-center gap-3">
                <span class="hidden md:block text-sm text-gray-600">Bonjour, <?php echo htmlspecialchars($parent['prenom']); ?></span>
                <div class="w-10 h-10 rounded-full bg-[#2B80F6] flex items-center justify-center text-white font-semibold shadow-md" aria-hidden="true">
                    <?php echo strtoupper(substr($parent['prenom'],0,1)) . strtoupper(substr($parent['nom'],0,1)); ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="max-w-6xl mx-auto px-4 pt-[6rem] pb-10">
        <!-- Breadcrumbs -->
        <nav class="mb-6 text-sm text-gray-500" aria-label="Fil d'Ariane">
            <ol class="flex items-center gap-2">
                <li><a href="index2.php" class="hover:text-[#2B80F6]">Accueil</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li class="text-gray-700 font-medium">Mon compte</li>
            </ol>
        </nav>

        <!-- Page title and actions -->
        <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight">Mon compte</h1>
                <p class="text-gray-500 mt-1">Gérez vos informations et préférences.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="ModifParent.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#2B80F6] text-white hover:bg-[#1a6ad8] focus:outline-none focus:ring-2 focus:ring-[#2B80F6]/50">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Modifier</span>
                </a>
                <a href="Deconnexion.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/50">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </div>

        <!-- Grid layout: Profile summary + Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Summary card -->
            <aside class="lg:col-span-1">
                <section class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-[#2B80F6] text-white font-bold flex items-center justify-center text-xl shadow">
                                <?php echo strtoupper(substr($parent['prenom'],0,1)) . strtoupper(substr($parent['nom'],0,1)); ?>
                            </div>
                            <div class="min-w-0">
                                <p class="text-lg font-semibold truncate"><?php echo htmlspecialchars($parent['prenom'] . ' ' . $parent['nom']); ?></p>
                                <p class="text-sm text-gray-500 truncate"><?php echo htmlspecialchars($parent['email']); ?></p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <div class="flex items-center gap-3 text-sm">
                                <span class="w-9 h-9 rounded-lg bg-blue-50 text-[#2B80F6] flex items-center justify-center"><i class="fa-solid fa-phone"></i></span>
                                <span class="truncate"><?php echo htmlspecialchars($parent['telephone']); ?></span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <span class="w-9 h-9 rounded-lg bg-blue-50 text-[#2B80F6] flex items-center justify-center"><i class="fa-solid fa-location-dot"></i></span>
                                <span class="truncate"><?php echo htmlspecialchars($parent['ville']); ?><?php if(!empty($parent['quartier'])){ echo ', ' . htmlspecialchars($parent['quartier']); } ?></span>
                            </div>
                            <?php if (!empty($parent['dateInscription'])): ?>
                            <div class="flex items-center gap-3 text-sm">
                                <span class="w-9 h-9 rounded-lg bg-blue-50 text-[#2B80F6] flex items-center justify-center"><i class="fa-regular fa-calendar"></i></span>
                                <span class="truncate">Inscrit le <?php echo htmlspecialchars(date('d/m/Y', strtotime($parent['dateInscription']))); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </aside>

            <!-- Detail cards -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations personnelles -->
                <section class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-lg font-semibold">Informations personnelles</h2>
                        <a href="ModifParent.php" class="text-sm text-[#2B80F6] hover:underline">Mettre à jour</a>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Prénom</p>
                            <p class="font-medium"><?php echo htmlspecialchars($parent['prenom']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Nom</p>
                            <p class="font-medium"><?php echo htmlspecialchars($parent['nom']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Email</p>
                            <p class="font-medium break-words"><?php echo htmlspecialchars($parent['email']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Téléphone</p>
                            <p class="font-medium"><?php echo htmlspecialchars($parent['telephone']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Ville</p>
                            <p class="font-medium"><?php echo htmlspecialchars($parent['ville']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Quartier</p>
                            <p class="font-medium"><?php echo htmlspecialchars($parent['quartier']); ?></p>
                        </div>
                    </div>
                </section>

                <!-- Répétiteurs contactés (placeholder) -->
                <section class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-lg font-semibold">Répétiteurs contactés</h2>
                        <a href="Repetiteurs.php" class="text-sm text-[#2B80F6] hover:underline">Trouver un répétiteur</a>
                    </div>
                    <div class="p-6">
                        <div class="rounded-2xl border border-dashed border-gray-200 p-8 text-center bg-gray-50">
                            <div class="mx-auto w-12 h-12 rounded-full bg-white shadow flex items-center justify-center text-[#2B80F6] mb-3">
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                            <h3 class="font-semibold mb-1">Aucun répétiteur contacté pour le moment</h3>
                            <p class="text-sm text-gray-600 mb-4">Parcourez les profils et entrez en contact avec des répétiteurs qualifiés.</p>
                            <a href="Repetiteurs2.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#2B80F6] text-white hover:bg-[#1a6ad8]">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <span>Découvrir les répétiteurs</span>
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Paramètres -->
                <section class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">Paramètres du compte</h2>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <a href="ModifParent.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50">
                                <i class="fa-solid fa-gear text-gray-700"></i>
                                <span>Modifier mes informations</span>
                            </a>
                            <a href="Deconnexion.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50">
                                <i class="fa-solid fa-right-from-bracket text-gray-700"></i>
                                <span>Se déconnecter</span>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script src="Scripts/tailwindcss.js"></script>
    <script src="Scripts/script.js"></script>
</body>
</html>