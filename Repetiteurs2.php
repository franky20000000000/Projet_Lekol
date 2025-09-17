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

// Récupérer les paramètres de filtrage
$search = isset($_GET['search']) ? $_GET['search'] : '';
$matiere = isset($_GET['matiere']) ? $_GET['matiere'] : '';
$niveau = isset($_GET['niveau']) ? $_GET['niveau'] : '';
$ville = isset($_GET['ville']) ? $_GET['ville'] : '';

// Pagination - nombre de résultats par page
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Construire la requête SQL avec filtres
$sql = "SELECT * FROM repetiteur WHERE 1=1";
$count_sql = "SELECT COUNT(*) as total FROM repetiteur WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (nom LIKE :search OR prenom LIKE :search)";
    $count_sql .= " AND (nom LIKE :search OR prenom LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if (!empty($matiere)) {
    $sql .= " AND matieres LIKE :matiere";
    $count_sql .= " AND matieres LIKE :matiere";
    $params[':matiere'] = '%' . $matiere . '%';
}

if (!empty($niveau)) {
    $sql .= " AND niveau_cible LIKE :niveau";
    $count_sql .= " AND niveau_cible LIKE :niveau";
    $params[':niveau'] = '%' . $niveau . '%';
}

if (!empty($ville)) {
    $sql .= " AND ville = :ville";
    $count_sql .= " AND ville = :ville";
    $params[':ville'] = $ville;
}

// Ajouter la limite et l'offset pour la pagination
$sql .= " LIMIT :limit OFFSET :offset";

// Exécuter la requête de comptage pour le total
$stmt_count = $pdo->prepare($count_sql);
foreach ($params as $key => $value) {
    if ($key === ':search' || $key === ':matiere') {
        $stmt_count->bindValue($key, $value, PDO::PARAM_STR);
    } else {
        $stmt_count->bindValue($key, $value);
    }
}
$stmt_count->execute();
$total_results = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_results / $limit);

// Exécuter la requête pour les résultats
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    if ($key === ':search' || $key === ':matiere') {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    } else {
        $stmt->bindValue($key, $value);
    }
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$repetiteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Répétiteurs | Lekol</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header id="navbar" class="fixed flex md:justify-around justify-between z-50 items-center p-4 shadow-[0_0.5px_6px_rgba(0,0,0,0.1)] w-full text-xl">
        <div>
            <p class="text-[#2B80F6] font-bold text-3xl">Lékol</p>
        </div>
        <div class="md:flex gap-5 hidden">
            <a class="transition-all duration-300 ease-in-out" href="index2.php">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About2.php">A Propos</a>
            <a class="transition-all duration-300 ease-in-out active" href="Repetiteurs.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets2.php">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out" href="Contact2.php">Contact</a>
        </div>

        <a href="ProfilParent.php">
            <div class="w-10 h-10 cursor-pointer rounded-full bg-[#2B80F6] text-white flex items-center relative right-[3rem] justify-center text-xl font-bold shadow-lg">
                <?php echo $initiales; ?>
            </div>
        </a>

        <div id="menu-hamburger" class="md:hidden absolute top-6 right-6 z-50 transition-all duration-[1s]">
            <img src="Images/menu.png" alt="">
        </div>
        
        <div id="mobilenav" class="md:hidden scale-0 flex flex-col gap-5  absolute top-[4.3rem] right-0 bg-white p-5 rounded-lg shadow-lg transition-all duration-[1s] transform origin-top-right">
            <a class="transition-all duration-300 ease-in-out" href="index2.php">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About2.php">A Propos</a>
            <a class="transition-all duration-300 ease-in-out active" href="Repetiteurs.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets2.php">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out" href="Contact2.php">Contact</a>
        </div>
    </header>

    <div class="pt-[7rem] md:mx-20 mx-10 top-[8rem]">
        <form method="GET" action="Repetiteurs.php" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div class="relative col-span-1 md:col-span-2 lg:col-span-1">
                <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">Rechercher un répétiteur</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Rechercher par nom..." 
                        class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B80F6] focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="filter-matiere" class="block text-sm font-medium text-gray-700 mb-1">Matière</label>
                <select id="filter-matiere" name="matiere" class="px-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B80F6]">
                    <option value="">Toutes les matières</option>
                    <option value="maths" <?php echo $matiere === 'maths' ? 'selected' : ''; ?>>Mathématiques</option>
                    <option value="physique" <?php echo $matiere === 'physique' ? 'selected' : ''; ?>>Physique</option>
                    <option value="chimie" <?php echo $matiere === 'chimie' ? 'selected' : ''; ?>>Chimie</option>
                    <option value="francais" <?php echo $matiere === 'francais' ? 'selected' : ''; ?>>Français</option>
                    <option value="anglais" <?php echo $matiere === 'anglais' ? 'selected' : ''; ?>>Anglais</option>
                </select>
            </div>

            <div>
                <label for="filter-niveau" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                <select id="filter-niveau" name="niveau" class="px-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B80F6]">
                    <option value="">Tous les niveaux</option>
                    <option value="primaire" <?php echo $niveau === 'primaire' ? 'selected' : ''; ?>>Primaire</option>
                    <option value="secondaire-1er-cycle" <?php echo $niveau === 'secondaire-1er-cycle' ? 'selected' : ''; ?>>Secondaire (1er Cycle)</option>
                    <option value="secondaire-2eme-cycle" <?php echo $niveau === 'secondaire-2eme-cycle' ? 'selected' : ''; ?>>Secondaire (2ème Cycle)</option>
                    <option value="superieur" <?php echo $niveau === 'superieur' ? 'selected' : ''; ?>>Supérieur</option>
                </select>
            </div>
            
            <div>
                <label for="filter-ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                <select id="filter-ville" name="ville" class="px-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B80F6]">
                    <option value="">Toutes les villes</option>
                    <option value="douala" <?php echo $ville === 'douala' ? 'selected' : ''; ?>>Douala</option>
                    <option value="yaounde" <?php echo $ville === 'yaounde' ? 'selected' : ''; ?>>Yaoundé</option>
                    <option value="bafoussam" <?php echo $ville === 'bafoussam' ? 'selected' : ''; ?>>Bafoussam</option>
                </select>
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-1 grid grid-cols-2 gap-4">
                <button type="submit" class="w-full px-4 py-2 text-white bg-[#2B80F6] rounded-lg hover:bg-opacity-90 transition-colors">
                    Rechercher
                </button>
                <a href="Repetiteurs.php" class="w-full px-4 py-2 text-center text-[#2B80F6] border border-[#2B80F6] rounded-lg hover:bg-[#2B80F6] hover:text-white transition-colors">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <section id="repetiteurs-container" class="pt-[7rem] md:mx-20 mx-5 grid md:grid-cols-2 grid-cols-1 gap-4 top-[8rem] h-auto">
        <?php if (count($repetiteurs) > 0): ?>
            <?php foreach ($repetiteurs as $repetiteur): ?>
                <article class="bg-gray-100 rounded-2xl p-6">
                    <a href="ProfilRepetiteurPublic.php?id=<?php echo $repetiteur['id']; ?>">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-full bg-pink-200 overflow-hidden flex items-center justify-center">
                                <?php if (!empty($repetiteur['piece_identite'])): ?>
                                    <img src="<?php echo htmlspecialchars($repetiteur['piece_identite']); ?>" alt="photo profil" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <img src="Images/student-7378903_1920.jpg" alt="photo profil" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-2xl font-semibold leading-tight"><?php echo htmlspecialchars($repetiteur['prenom'] . ' ' . $repetiteur['nom']); ?></h3>

                                <!-- Étoiles (à remplacer par un système de notation réel si disponible) -->
                                <div class="flex items-center gap-1 mt-1">
                                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                    <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                                </div>
                                <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($repetiteur['matieres']); ?></p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 mt-4">
                            <?php echo htmlspecialchars($repetiteur['description']); ?>
                        </p>
                    </a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-2 text-center py-10">
                <p class="text-gray-500 text-lg">Aucun répétiteur trouvé avec ces critères de recherche.</p>
            </div>
        <?php endif; ?>
    </section>

    <?php if ($total_pages > 1 && $page < $total_pages): ?>
    <div class="flex justify-center mt-6 mb-6">
        <a href="Repetiteurs.php?<?php 
            echo http_build_query(array_merge($_GET, ['page' => $page + 1]));
        ?>" class="bg-[#2B80F6] p-2 px-5 rounded-lg text-white shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-[#2B80F6]-400/50">
            Voir plus
        </a>
    </div>
    <?php endif; ?>

    <script src="Scripts/script.js"></script>
    <script src="Scripts/tailwindcss.js"></script>
</body>
</html>