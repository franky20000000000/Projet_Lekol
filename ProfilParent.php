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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profil Parent</title>
</head>
<body class="bg-gray-50 min-h-screen">
    <!------------------------------------ entete ------------------------------------>
    <header id="navbar" class="fixed flex px-10 justify-between z-50 items-center p-4 shadow-[0_0.5px_6px_rgba(0,0,0,0.1)] w-full text-xl">
        <a href="index2.php">
            <p class="text-[#2B80F6] font-bold text-3xl cursor-pointer">Lékol</p>
        </a>
        <div class="w-10 h-10 rounded-full bg-[#2B80F6] flex items-center justify-center text-white font-medium">
            <?php echo strtoupper(substr($parent['prenom'],0,1)) . strtoupper(substr($parent['nom'],0,1)); ?>
        </div>
    </header>

    <main class="container mx-auto px-4 pt-[6rem] pb-5">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-bold">Mon Compte</h1>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold mb-4">Informations Personnelles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-lg font-medium mb-1">Prénom</label>
                            <p class="text-gray-800"><?php echo htmlspecialchars($parent['prenom']); ?></p>
                        </div>
                        <div>
                            <label class="block text-lg font-medium mb-1">Nom</label>
                            <p class="text-gray-800"><?php echo htmlspecialchars($parent['nom']); ?></p>
                        </div>
                        <div>
                            <label class="block text-lg font-medium mb-1">Email</label>
                            <p class="text-gray-800"><?php echo htmlspecialchars($parent['email']); ?></p>
                        </div>
                        <div>
                            <label class="block text-lg font-medium mb-1">Téléphone</label>
                            <p class="text-gray-800"><?php echo htmlspecialchars($parent['telephone']); ?></p>
                        </div>
                        <div>
                            <label class="block text-lg font-medium mb-1">Ville</label>
                            <p class="text-gray-800"><?php echo htmlspecialchars($parent['ville']); ?></p>
                        </div>
                        <div>
                            <label class="block text-lg font-medium mb-1">Quartier</label>
                            <p class="text-gray-800"><?php echo htmlspecialchars($parent['quartier']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Répetiteurs Contactés</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-600 font-medium">AK</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">Amara Kone</h3>
                                    <p class="text-sm text-gray-600">Mathématiques, Physique - 2500 FCFA/h</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">Contacté</span>
                        </div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-600 font-medium">ND</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">Nadia Diallo</h3>
                                    <p class="text-sm text-gray-600">Français, Anglais - 2000 FCFA/h</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">En cours</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Paramètres du Compte</h2>
                   <div class="space-y-4">
                        <div class="pt-4 border-t border-gray-100 flex justify-between">
                            <!-- Bouton Modifier -->
                            <a href="ModifParent.php" class="px-4 py-2 bg-[#2B80F6] text-white rounded-lg hover:bg-[#1a6ad8] transition">
                                <i class="fas fa-edit mr-2"></i>Modifier mes informations
                            </a>

                            <!-- Bouton Déconnexion -->
                            <a href="Deconnexion.php" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-800 transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                            </a>
                        </div>
                   </div>

                </div>
            </div>
        </div>
    </main>

    <script src="Scripts/tailwindcss.js"></script>
    <script src="Scripts/script.js"></script>
</body>
</html>