<?php
session_start();

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id'])){
    header("Location: inscriptionparent.php");
    exit;
}

// Connexion à la base de données
$host = "localhost";
$dbname = "lekol";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer l'ID du répétiteur depuis l'URL
if (!isset($_GET['id'])) {
    header("Location: Repetiteurs.php");
    exit;
}

$repetiteur_id = (int)$_GET['id'];

// Récupérer les informations du répétiteur
$sql = "SELECT * FROM repetiteur WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $repetiteur_id, PDO::PARAM_INT);
$stmt->execute();
$repetiteur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$repetiteur) {
    header("Location: Repetiteurs.php");
    exit;
}

// Exemple de données récupérées après inscription
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];

// On prend la première lettre du prénom et du nom
$initiales = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?php echo htmlspecialchars($repetiteur['prenom'] . ' ' . $repetiteur['nom']); ?> | Lekol</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .badge {
            background: #e5e7eb;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            color: #374151;
        }
        .availability-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }
        .availability-available {
            background: #10b981;
        }
        .card {
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header id="navbar" class="fixed flex md:justify-between justify-between px-20 z-50 items-center p-4 shadow-[0_0.5px_6px_rgba(0,0,0,0.1)] w-full text-xl">
        <div>
            <a href="index2.php"><p class="text-[#2B80F6] font-bold text-3xl">Lékol</p></a>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="ProfilParent.php" class="w-10 h-10 cursor-pointer rounded-full bg-[#2B80F6] text-white flex items-center justify-center text-xl font-bold shadow-lg">
                <?php echo $initiales; ?>
            </a>
        </div>
    </header>

    <main class="container mx-auto px-4 py-20 mt-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar avec informations principales -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative mb-4">
                            <?php if (!empty($repetiteur['piece_identite'])): ?>
                                <img src="<?php echo htmlspecialchars($repetiteur['piece_identite']); ?>" 
                                     alt="Photo de profil" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow">
                            <?php else: ?>
                                <img src="Images/student-7378903_1920.jpg" 
                                     alt="Photo de profil" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow">
                            <?php endif; ?>
                            <span class="availability-dot availability-available absolute bottom-3 right-3"></span>
                        </div>
                        <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($repetiteur['prenom'] . ' ' . $repetiteur['nom']); ?></h1>
                        <p class="text-gray-600"><?php echo htmlspecialchars($repetiteur['filiere']); ?></p>
                        <div class="flex items-center mt-2">
                            <!-- Étoiles -->
                            <div class="flex items-center gap-1 mt-1">
                                <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="CurrentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 极l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118极1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.极-3.57z"/></svg>
                                <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 极00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0极-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.极4 2.21a1 1 0 00-.365 1.118l1.16 3.57c极02.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                            </div>
                            <span class="ml-2 text-gray-600">4.0 (12 avis)</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Disponibilités</h3>
                            <ul class="space-y-2">
                                <li class="flex justify-between">
                                    <span>Lundi</span>
                                    <span>18h - 20h</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Mercredi</span>
                                    <span>16h - 20h</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Samedi</span>
                                    <span>9h - 12h, 14h - 18h</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-2">Localisation</h3>
                            <p class="flex items-center">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                <?php echo htmlspecialchars($repetiteur['ville'] . ', ' . $repetiteur['quartier']); ?>
                            </p>
                        </div>

                        <button class="w-full py-3 bg-[#2B80F6] text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                            <i class="fas fa-phone-alt mr-2"></i> Appeler ce répétiteur
                        </button>

                        <button class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                            <i class="fas fa-comment-dots mr-2"></i> Envoyer un message
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Section À propos -->
                <div class="bg-white rounded-xl shadow-lg p-6 card">
                    <h2 class="text-xl font-bold mb-4">À propos de moi</h2>
                    <p class="text-gray-700">
                        <?php echo !empty($repetiteur['description']) ? nl2br(htmlspecialchars($repetiteur['description'])) : 'Aucune description fournie.'; ?>
                    </p>
                </div>

                <!-- Section Informations académiques -->
                <div class="bg-white rounded-xl shadow-lg p-6 card">
                    <h2 class="text-xl font-bold mb-4">Informations académiques</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-600">Statut académique</h3>
                            <p><?php echo !empty($repetiteur['niveau_etudes']) ? htmlspecialchars($repetiteur['niveau_etudes']) : 'Non spécifié'; ?></p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-600">Établissement</h3>
                            <p><?php echo !empty($repetiteur['universite']) ? htmlspecialchars($repetiteur['universite']) : 'Non spécifié'; ?></p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-600">Filière</h3>
                            <p><?php echo !empty($repetiteur['filiere']) ? htmlspecialchars($repetiteur['filiere']) : 'Non spécifié'; ?></p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-600">Âge</h3>
                            <p>
                                <?php 
                                if (!empty($repetiteur['date_naissance'])) {
                                    $birthDate = new DateTime($repetiteur['date_naissance']);
                                    $today = new DateTime();
                                    $age = $today->diff($birthDate)->y;
                                    echo $age . ' ans';
                                } else {
                                    echo 'Non spécifié';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section Compétences -->
                <div class="bg-white rounded-xl shadow-lg p-6 card">
                    <h2 class="text-xl font-bold mb-4">Compétences</h2>
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-600 mb-2">Matières enseignées</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            if (!empty($repetiteur['matieres'])) {
                                $matieres = explode(',', $repetiteur['matieres']);
                                foreach ($matieres as $matiere) {
                                    echo '<span class="badge">' . htmlspecialchars(trim($matiere)) . '</span>';
                                }
                            } else {
                                echo '<p>Aucune matière spécifiée</p>';
                            }
                            ?>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-600 mb-2">Niveaux enseignés</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            if (!empty($repetiteur['niveau_cible'])) {
                                $niveaux = explode(',', $repetiteur['niveau_cible']);
                                foreach ($niveaux as $niveau) {
                                    echo '<span class="badge">' . htmlspecialchars(trim($niveau)) . '</span>';
                                }
                            } else {
                                echo '<p>Aucun niveau spécifié</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Section Informations personnelles -->
                <div class="bg-white rounded-xl shadow-lg p-6 card">
                    <h2 class="text-xl font-bold mb-4">Informations personnelles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-600">Nom</h3>
                            <p><?php echo htmlspecialchars($repetiteur['nom']); ?></p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-600">Prénom</h3>
                            <p><?php echo htmlspecialchars($repetiteur['prenom']); ?></p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-600">Email</h3>
                            <p><?php echo htmlspecialchars($repetiteur['email']); ?></p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-600">Téléphone</h3>
                            <p><?php echo htmlspecialchars($repetiteur['telephone']); ?></p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-600">Ville</h3>
                            <p><?php echo htmlspecialchars($repetiteur['ville']); ?></p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-600">Quartier</h3>
                            <p><?php echo htmlspecialchars($repetiteur['quartier']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Section Expérience -->
                <div class="bg-white rounded-xl shadow-lg p-6 card">
                    <h2 class="text-xl font-bold mb-4">Expérience</h2>
                    <p class="text-gray-700">
                        3 ans d'expérience en soutien scolaire, ayant accompagné plus de 15 élèves dans leur progression académique. Major de promotion lors de ma première année à l'IAI Cameroun.
                    </p>
                </div>

                <!-- Section Zone de déplacement -->
                <div class="bg-white rounded-xl shadow-lg p-6 card">
                    <h2 class="text-xl font-bold mb-4">Zone de déplacement</h2>
                    <p class="text-gray-700">
                        <?php echo !empty($repetiteur['zones']) ? htmlspecialchars($repetiteur['zones']) : 'Aucune zone spécifiée'; ?>
                    </p>
                </div>

                <!-- Section Avis -->
                <div class="bg-white rounded-xl shadow-lg p-6 card">
                    <h2 class="text-xl font-bold mb-4">Avis (12)</h2>
                    <div class="space-y-4">
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold">Marie K.</h3>
                                    <!-- Étoiles -->
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 极 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.极6 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69极3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 极0" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                        <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">Il y a 2 semaines</span>
                            </div>
                            <p class="mt-2 text-gray-700">Koffi a redonné confiance à ma fille. Ses explications sont claires et il est très ponctuel. Je le recommande vivement !</p>
                        </div>
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold">Paul A.</h3>
                                    <!-- Étoiles -->
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 极.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0极-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.极39-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0极1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 极00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927极.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                        <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.极1a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57极"/></svg>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">Il y a 1 mois</span>
                            </div>
                            <p class="mt-2 text-gray-700">Mon fils a progressé de 3 points en maths depuis qu'il travaille avec Koffi. Très professionnel.</p>
                        </div>
                    </div>
                    <button class="mt-4 text-blue-600 font-semibold flex items-center">
                        Voir tous les avis <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script src="Scripts/script.js"></script>
</body>
</html>