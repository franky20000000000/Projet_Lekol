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
$parent_id = $_SESSION['id'];

// Classe pour gérer les avis
class AvisManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Ajouter un avis
    public function ajouterAvis($parent_id, $repetiteur_id, $note, $commentaire) {
        $sql = "INSERT INTO avis (parent_id, repetiteur_id, note, commentaire) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$parent_id, $repetiteur_id, $note, $commentaire]);
    }
    
    // Récupérer les avis d'un répétiteur (CORRIGÉ)
    public function getAvisParRepetiteur($repetiteur_id, $limit = 10) {
        // Utiliser un entier pour la limite
        $limit = (int)$limit;
        $sql = "SELECT a.*, p.nom as parent_nom, p.prenom as parent_prenom 
                FROM avis a 
                LEFT JOIN parent p ON a.parent_id = p.id 
                WHERE a.repetiteur_id = ? AND a.statut = 'approuve' 
                ORDER BY a.date_creation DESC 
                LIMIT " . $limit;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$repetiteur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Calculer la moyenne des notes
    public function getMoyenneNotes($repetiteur_id) {
        $sql = "SELECT AVG(note) as moyenne, COUNT(*) as total 
                FROM avis
                WHERE repetiteur_id = ? AND statut = 'approuve'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$repetiteur_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Compter le nombre d'avis d'un parent pour un répétiteur
    public function getNombreAvisParent($parent_id, $repetiteur_id) {
        $sql = "SELECT COUNT(*) as count FROM avis 
                WHERE parent_id = ? AND repetiteur_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$parent_id, $repetiteur_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }
}

// Instancier le gestionnaire d'avis
$avisManager = new AvisManager($pdo);

// Traitement du formulaire d'avis
$message_avis = '';
if ($_POST && isset($_POST['submit_avis'])) {
    $note = isset($_POST['note']) ? filter_var($_POST['note'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 5]
    ]) : null;
    
    $commentaire = htmlspecialchars(trim($_POST['commentaire'] ?? ''));
    
    if ($note && !empty($commentaire)) {
        // Autoriser jusqu'à 5 avis par parent pour ce répétiteur
        $nbAvis = $avisManager->getNombreAvisParent($parent_id, $repetiteur_id);
        if ($nbAvis < 5) {
            if ($avisManager->ajouterAvis($parent_id, $repetiteur_id, $note, $commentaire)) {
                $restants = 4 - $nbAvis; // après insertion, il en restera au max (5 - (nbAvis+1))
                $message_avis = '<div class="alert alert-success">Votre avis a été ajouté avec succès ! Il vous reste ' . max(0, $restants) . ' avis possibles.</div>';
            } else {
                $message_avis = '<div class="alert alert-error">Une erreur s\'est produite lors de l\'ajout de l\'avis.</div>';
            }
        } else {
            $message_avis = '<div class="alert alert-error">Limite atteinte : vous avez déjà laissé 5 avis pour ce répétiteur.</div>';
        }
    } else {
        $message_avis = '<div class="alert alert-error">Veuillez donner une note et un commentaire valides.</div>';
    }
}

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

// Récupérer les avis et la moyenne
$avis = $avisManager->getAvisParRepetiteur($repetiteur_id);
$moyenne = $avisManager->getMoyenneNotes($repetiteur_id);
$note_moyenne = $moyenne['moyenne'] ? round($moyenne['moyenne'], 1) : 0;
$total_avis = $moyenne['total'] ? $moyenne['total'] : 0;

// Vérifier si le parent peut noter
$nbAvisParent = $avisManager->getNombreAvisParent($parent_id, $repetiteur_id);
$peut_noter = ($nbAvisParent < 5);

// Exemple de données récupérées après inscription
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
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
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            cursor: pointer;
            font-size: 24px;
            color: #d1d5db;
            transition: color 0.2s;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #fbbf24;
        }
        .star-rating input:checked + label {
            color: #fbbf24;
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
        .star-filled {
            color: #fbbf24;
        }
        .star-empty {
            color: #d1d5db;
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
        <?php echo $message_avis; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar avec informations principales -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative mb-4">
                            <?php if (!empty($repetiteur['photo_profil'])): ?>
                                <img src="<?php echo htmlspecialchars($repetiteur['photo_profil']); ?>" 
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
                            <!-- Étoiles dynamiques -->
                            <div class="flex items-center gap-1 mt-1">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= floor($note_moyenne)) {
                                        echo '<svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>';
                                    } else {
                                        echo '<svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04-2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>';
                                    }
                                }
                                ?>
                            </div>
                            <span class="ml-2 text-gray-600"><?php echo $note_moyenne; ?> (<?php echo $total_avis; ?> avis)</span>
                        </div>
                    </div>

                    <!-- Formulaire d'avis (seulement si le parent peut noter) -->
                    <?php if ($peut_noter): ?>
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold mb-3">Donner votre avis</h3>
                        <form method="post" class="space-y-3">
                            <div class="star-rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?php echo $i; ?>" name="note" value="<?php echo $i; ?>" />
                                    <label for="star<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <textarea name="commentaire" placeholder="Votre commentaire..." class="w-full border rounded p-2 text-sm" rows="3" required></textarea>
                            <button type="submit" name="submit_avis" class="w-full bg-[#2B80F6] text-white py-2 rounded hover:bg-blue-700 transition">
                                Publier l'avis
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>

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

                <!-- Section Avis dynamique -->
                <div class="bg-white rounded-xl shadow-lg p-6 card">
                    <h2 class="text-xl font-bold mb-4">Avis (<?php echo $total_avis; ?>)</h2>
                    
                    <?php if (count($avis) > 0): ?>
                        <div class="space-y-4">
                            <?php foreach ($avis as $avis_item): ?>
                                <div class="border-b pb-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold">
                                                <?php echo htmlspecialchars($avis_item['parent_prenom'] . ' ' . substr($avis_item['parent_nom'], 0, 1) . '.'); ?>
                                            </h3>
                                            <!-- Étoiles dynamiques pour chaque avis -->
                                            <div class="flex items-center gap-1 mt-1">
                                                <?php
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $avis_item['note']) {
                                                        echo '<svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04-2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>';
                                                    } else {
                                                        echo '<svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04-2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500">
                                            <?php
                                            $date_avis = new DateTime($avis_item['date_creation']);
                                            $aujourdhui = new DateTime();
                                            $difference = $date_avis->diff($aujourdhui);
                                            
                                            if ($difference->y > 0) {
                                                echo 'Il y a ' . $difference->y . ' an' . ($difference->y > 1 ? 's' : '');
                                            } elseif ($difference->m > 0) {
                                                echo 'Il y a ' . $difference->m . ' mois';
                                            } elseif ($difference->d > 0) {
                                                echo 'Il y a ' . $difference->d . ' jour' . ($difference->d > 1 ? 's' : '');
                                            } else {
                                                echo 'Aujourd\'hui';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <p class="mt-2 text-gray-700"><?php echo nl2br(htmlspecialchars($avis_item['commentaire'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">Aucun avis pour le moment.</p>
                    <?php endif; ?>
                    
                    <?php if ($total_avis > 2): ?>
                        <button class="mt-4 text-blue-600 font-semibold flex items-center">
                            Voir tous les avis <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Script pour le système de notation par étoiles
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating label');
            
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.htmlFor.replace('star', '');
                    // Vous pouvez ajouter ici un feedback visuel supplémentaire
                });
            });
        });
    </script>

    <script src="Scripts/script.js"></script>
</body>
</html>