<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id']) || $_SESSION['type_utilisateur'] !== 'repetiteur') {
    header("Location: Connexion.html");
    exit;
}

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

// Récupérer les informations du répétiteur
$repetiteur_id = $_SESSION['id'];
$sql = "SELECT * FROM repetiteur WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$repetiteur_id]);
$repetiteur = $stmt->fetch(PDO::FETCH_ASSOC);

// Traitement de la mise à jour des informations
if (isset($_POST['update'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $ville = $_POST['ville'];
    $quartier = $_POST['quartier'];
    $niveau = $_POST['niveau'];
    $universite = $_POST['universite'];
    $filiere = $_POST['filiere'];
    $matiere = $_POST['matiere'];
    $niveauCible = $_POST['niveauCible'];
    $zone = $_POST['zone'];
    $description = $_POST['description'];
    
    // Mise à jour dans la base de données
    $sql_update = "UPDATE repetiteur SET nom=?, prenom=?, email=?, telephone=?, ville=?, quartier=?, 
                  niveau_etudes=?, universite=?, filiere=?, matieres=?, niveau_cible=?, zones=?, description=? 
                  WHERE id=?";
    $stmt_update = $pdo->prepare($sql_update);
    $ok = $stmt_update->execute([
        $nom, $prenom, $email, $telephone, $ville, $quartier, $niveau, $universite, 
        $filiere, $matiere, $niveauCible, $zone, $description, $repetiteur_id
    ]);
    
    if ($ok) {
        // Mettre à jour les informations en session
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        
        // Recharger les informations du répétiteur
        $stmt->execute([$repetiteur_id]);
        $repetiteur = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $message_success = "Vos informations ont été mises à jour avec succès!";
    } else {
        $message_error = "Une erreur s'est produite lors de la mise à jour.";
    }
}

// Traitement de la déconnexion
if (isset($_GET['deconnexion'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Répétiteur - Lekol</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="ProfilRepetiteur.css">
    <style>
        .edit-mode {
            display: none;
        }
        .view-mode {
            display: block;
        }
        .editable-section {
            position: relative;
        }
        .edit-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f1f1f1;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .edit-btn:hover {
            background: #e1e1e1;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn-save {
            background: #2B80F6;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-cancel {
            background: #f1f1f1;
            color: #333;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header id="navbar" class="fixed flex md:justify-between justify-between z-50 items-center p-4 shadow-[0_0.5px_6px_rgba(0,0,0,0.1)] w-full text-xl">
        <div>
            <a href="index.php"><p class="text-[#2B80F6] font-bold text-3xl">Lékol</p></a>
        </div>
        
        <div class="flex items-center gap-4">
            <span>Bonjour, <?php echo htmlspecialchars($repetiteur['prenom'] . ' ' . $repetiteur['nom']); ?></span>
            <a href="?deconnexion=1" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Déconnexion</a>
        </div>

        <div id="menu-hamburger" class="md:hidden absolute top-6 right-6 z-50 transition-all duration-[1s]">
            <img src="Images/menu.png" alt="">
        </div>
    </header>

    <main class="container mx-auto px-4 py-20">
        <?php if (isset($message_success)): ?>
            <div class="alert alert-success"><?php echo $message_success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($message_error)): ?>
            <div class="alert alert-error"><?php echo $message_error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar avec informations principales -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                        <div class="flex flex-col items-center mb-6">
                            <div class="relative mb-4">
                                <img src="Images/student-7378903_1920.jpg" 
                                     alt="Photo de profil" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow">
                                <span class="availability-dot availability-available absolute bottom-3 right-3"></span>
                            </div>
                            <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($repetiteur['prenom'] . ' ' . $repetiteur['nom']); ?></h1>
                            <p class="text-gray-600"><?php echo htmlspecialchars($repetiteur['filiere']); ?></p>
                            <div class="flex items-center mt-2">
                                <!-- Étoiles -->
                                <div class="flex items-center gap-1 mt-1">
                                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                    <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
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
                                <i class="fas fa-comment-dots mr-2"></i> Appeler ce répétiteur
                            </button>

                            <button class="w-full py-3 bg-[#2B80F6] text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                                <i class="fas fa-comment-dots mr-2"></i> Envoyer un message
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div class="lg:col-span-2 space-y-8 md:mt-16">
                    <!-- Section À propos -->
                    <div class="bg-white rounded-xl shadow-lg p-6 card editable-section">
                        <button type="button" class="edit-btn" onclick="toggleEdit('about')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <h2 class="text-xl font-bold mb-4">À propos de moi</h2>
                        
                        <div id="about-view" class="view-mode">
                            <p class="text-gray-700">
                                <?php echo !empty($repetiteur['description']) ? nl2br(htmlspecialchars($repetiteur['description'])) : 'Aucune description fournie.'; ?>
                            </p>
                        </div>
                        
                        <div id="about-edit" class="edit-mode">
                            <textarea name="description" class="w-full border rounded p-2" rows="5"><?php echo htmlspecialchars($repetiteur['description']); ?></textarea>
                            <div class="form-actions">
                                <button type="button" class="btn-save" onclick="toggleEdit('about')">Enregistrer</button>
                                <button type="button" class="btn-cancel" onclick="toggleEdit('about')">Annuler</button>
                            </div>
                        </div>
                    </div>

                    <!-- Section Informations académiques -->
                    <div class="bg-white rounded-xl shadow-lg p-6 card editable-section">
                        <button type="button" class="edit-btn" onclick="toggleEdit('academic')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <h2 class="text-xl font-bold mb-4">Informations académiques</h2>
                        
                        <div id="academic-view" class="view-mode">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="font-semibold text-gray-600">Statut académique</h3>
                                    <p><?php echo htmlspecialchars($repetiteur['niveau_etudes']); ?></p>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-600">Établissement</h3>
                                    <p><?php echo htmlspecialchars($repetiteur['universite']); ?></p>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-600">Filière</h3>
                                    <p><?php echo htmlspecialchars($repetiteur['filiere']); ?></p>
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
                        
                        <div id="academic-edit" class="edit-mode">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="font-semibold text-gray-600">Statut académique</h3>
                                    <select name="niveau" class="w-full border rounded p-2">
                                        <option value="Nouveau bachelier" <?php echo $repetiteur['niveau_etudes'] == 'Nouveau bachelier' ? 'selected' : ''; ?>>Nouveau bachelier</option>
                                        <option value="Etudiant en cycle BTS" <?php echo $repetiteur['niveau_etudes'] == 'Etudiant en cycle BTS' ? 'selected' : ''; ?>>Etudiant en cycle BTS</option>
                                        <option value="Etudiant en cycle licence" <?php echo $repetiteur['niveau_etudes'] == 'Etudiant en cycle licence' ? 'selected' : ''; ?>>Etudiant en cycle licence</option>
                                        <option value="Etudiant en cycle Master" <?php echo $repetiteur['niveau_etudes'] == 'Etudiant en cycle Master' ? 'selected' : ''; ?>>Etudiant en cycle Master</option>
                                        <option value="Autre" <?php echo $repetiteur['niveau_etudes'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                                    </select>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-600">Établissement</h3>
                                    <input type="text" name="universite" value="<?php echo htmlspecialchars($repetiteur['universite']); ?>" class="w-full border rounded p-2">
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-600">Filière</h3>
                                    <input type="text" name="filiere" value="<?php echo htmlspecialchars($repetiteur['filiere']); ?>" class="w-full border rounded p-2">
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-save" onclick="toggleEdit('academic')">Enregistrer</button>
                                <button type="button" class="btn-cancel" onclick="toggleEdit('academic')">Annuler</button>
                            </div>
                        </div>
                    </div>

                    <!-- Section Compétences -->
                    <div class="bg-white rounded-xl shadow-lg p-6 card editable-section">
                        <button type="button" class="edit-btn" onclick="toggleEdit('skills')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <h2 class="text-xl font-bold mb-4">Compétences</h2>
                        
                        <div id="skills-view" class="view-mode">
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
                        
                        <div id="skills-edit" class="edit-mode">
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-600 mb-2">Matières enseignées</h3>
                                <input type="text" name="matiere" value="<?php echo htmlspecialchars($repetiteur['matieres']); ?>" class="w-full border rounded p-2" placeholder="Séparer par des virgules">
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-600 mb-2">Niveaux enseignés</h3>
                                <select name="niveauCible" class="w-full border rounded p-2">
                                    <option value="Primaire" <?php echo $repetiteur['niveau_cible'] == 'Primaire' ? 'selected' : ''; ?>>Primaire</option>
                                    <option value="Secondaire" <?php echo $repetiteur['niveau_cible'] == 'Secondaire' ? 'selected' : ''; ?>>Secondaire</option>
                                    <option value="Primaire,Secondaire" <?php echo $repetiteur['niveau_cible'] == 'Primaire,Secondaire' ? 'selected' : ''; ?>>Primaire et Secondaire</option>
                                </select>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-save" onclick="toggleEdit('skills')">Enregistrer</button>
                                <button type="button" class="btn-cancel" onclick="toggleEdit('skills')">Annuler</button>
                            </div>
                        </div>
                    </div>

                    <!-- Section Informations personnelles -->
                    <div class="bg-white rounded-xl shadow-lg p-6 card editable-section">
                        <button type="button" class="edit-btn" onclick="toggleEdit('personal')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <h2 class="text-xl font-bold mb-4">Informations personnelles</h2>
                        
                        <div id="personal-view" class="view-mode">
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
                        
                        <div id="personal-edit" class="edit-mode">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="font-semibold text-gray-600">Nom</h3>
                                    <input type="text" name="nom" value="<?php echo htmlspecialchars($repetiteur['nom']); ?>" class="w-full border rounded p-2">
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-600">Prénom</h3>
                                    <input type="text" name="prenom" value="<?php echo htmlspecialchars($repetiteur['prenom']); ?>" class="w-full border rounded p-2">
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-600">Email</h3>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($repetiteur['email']); ?>" class="w-full border rounded p-2">
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-600">Téléphone</h3>
                                    <input type="text" name="telephone" value="<?php echo htmlspecialchars($repetiteur['telephone']); ?>" class="w-full border rounded p-2">
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-600">Ville</h3>
                                    <input type="text" name="ville" value="<?php echo htmlspecialchars($repetiteur['ville']); ?>" class="w-full border rounded p-2">
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-600">Quartier</h3>
                                    <input type="text" name="quartier" value="<?php echo htmlspecialchars($repetiteur['quartier']); ?>" class="w-full border rounded p-2">
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-save" onclick="toggleEdit('personal')">Enregistrer</button>
                                <button type="button" class="btn-cancel" onclick="toggleEdit('personal')">Annuler</button>
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
                    <div class="bg-white rounded-xl shadow-lg p-6 card editable-section">
                        <button type="button" class="edit-btn" onclick="toggleEdit('zones')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <h2 class="text-xl font-bold mb-4">Zone de déplacement</h2>
                        
                        <div id="zones-view" class="view-mode">
                            <p class="text-gray-700">
                                <?php echo !empty($repetiteur['zones']) ? htmlspecialchars($repetiteur['zones']) : 'Aucune zone spécifiée'; ?>
                            </p>
                        </div>
                        
                        <div id="zones-edit" class="edit-mode">
                            <input type="text" name="zone" value="<?php echo htmlspecialchars($repetiteur['zones']); ?>" class="w-full border rounded p-2" placeholder="Séparer les quartiers par des virgules">
                            <div class="form-actions">
                                <button type="button" class="btn-save" onclick="toggleEdit('zones')">Enregistrer</button>
                                <button type="button" class="btn-cancel" onclick="toggleEdit('zones')">Annuler</button>
                            </div>
                        </div>
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
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
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
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                            <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
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
            
            <div class="mt-8 text-center">
                <input type="submit" name="update" value="Enregistrer toutes les modifications" class="bg-[#2B80F6] text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 cursor-pointer">
            </div>
        </form>
    </main>

    <script>
        function toggleEdit(section) {
            const viewElement = document.getElementById(`${section}-view`);
            const editElement = document.getElementById(`${section}-edit`);
            
            if (viewElement.style.display === 'none') {
                viewElement.style.display = 'block';
                editElement.style.display = 'none';
            } else {
                viewElement.style.display = 'none';
                editElement.style.display = 'block';
            }
        }
    </script>

    <script src="Scripts/ProfilRepetiteur.js"></script>
    <script src="Scripts/tailwindcss.js"></script>
    <script src="Scripts/scriptIncrement.js"></script>
    <script src="Scripts/script.js"></script>
</body>
</html>