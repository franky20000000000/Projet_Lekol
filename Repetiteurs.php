<?php
// Connexion BD et récupération dynamique (sans obligation de session)
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

// Filtres
$search = isset($_GET['search']) ? $_GET['search'] : '';
$matiere = isset($_GET['matiere']) ? $_GET['matiere'] : '';
$niveau = isset($_GET['niveau']) ? $_GET['niveau'] : '';
$ville = isset($_GET['ville']) ? $_GET['ville'] : '';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }
$offset = ($page - 1) * $limit;

// Requêtes
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

$sql .= " LIMIT :limit OFFSET :offset";

// Total
$stmt_count = $pdo->prepare($count_sql);
foreach ($params as $key => $value) {
    if ($key === ':search' || $key === ':matiere') {
        $stmt_count->bindValue($key, $value, PDO::PARAM_STR);
    } else {
        $stmt_count->bindValue($key, $value);
    }
}
$stmt_count->execute();
$total_results = (int)$stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = (int)ceil($total_results / $limit);

// Liste
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répétiteurs | Lekol</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header id="navbar" class="fixed flex md:justify-around justify-between z-50 items-center p-4 shadow-[0_0.5px_6px_rgba(0,0,0,0.1)] w-full text-xl">
        <div>
            <p class="text-[#2B80F6] font-bold text-3xl">Lékol</p>
        </div>
        <div class="md:flex gap-5 hidden">
            <a class="transition-all duration-300 ease-in-out" href="index.php">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About.php">A Propos</a>
            <a class="transition-all duration-300 ease-in-out active" href="">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets.php">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out" href="Contact.php">Contact</a>
        </div>
        <div class="flex flex-row justify-between gap-[4rem] items-center">
            <a href="Choix.php"><button class="bg-[#2B80F6] p-2 px-5 rounded-lg text-white hidden md:block shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-[#2B80F6]-400/50">S'inscrire</button></a>

            <a href="Connexion.php">
                <div class="md:w-[3rem] md:h-[3rem] h-[2rem] w-[2rem] cursor-pointer rounded-full bg-[#2B80F6] text-white flex items-center relative right-[3rem] justify-center shadow-lg">
                    <i class="fa-solid fa-user"></i>
                </div>
            </a>
        </div>

        <div id="menu-hamburger" class="md:hidden absolute top-6 right-6 z-50 transition-all duration-[1s]">
            <img src="Images/menu.png" alt="">
        </div>
        
        <div id="mobilenav" class="md:hidden  flex flex-col gap-5  absolute top-[4.3rem] right-0 bg-white p-5 rounded-lg shadow-lg transition-all duration-[1s] scale-0 transform origin-top-right">
            <a class="transition-all duration-300 ease-in-out" href="index.html">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About.html">A Propos</a>
            <a class="transition-all duration-300 ease-in-out active" href="Repetiteurs.html">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets.html">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out" href="Contact.html">Contact</a>
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
                            <option value="mathematiques" <?php echo $matiere==='mathematiques'?'selected':''; ?>>Mathématiques</option>
                            <option value="physique" <?php echo $matiere==='physique'?'selected':''; ?>>Physique</option>
                            <option value="chimie" <?php echo $matiere==='chimie'?'selected':''; ?>>Chimie</option>
                            <option value="svt" <?php echo $matiere==='svt'?'selected':''; ?>>SVT</option>
                            <option value="informatique" <?php echo $matiere==='informatique'?'selected':''; ?>>Informatique</option>
                            <option value="litterature" <?php echo $matiere==='litterature'?'selected':''; ?>>Littérature</option>
                            <option value="langues" <?php echo $matiere==='langues'?'selected':''; ?>>Langues</option>
                            <option value="anglais" <?php echo $matiere==='anglais'?'selected':''; ?>>Anglais</option>
                            <option value="philosophie" <?php echo $matiere==='philosophie'?'selected':''; ?>>Philosophie</option>
                            </select>
                    </div>

                    <div>
                        <label for="filter-niveau" class="block text-sm font-medium text-gray-700 mb-1">Niveau d'études</label>
                        <select id="filter-niveau" name="niveau" class="px-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B80F6]">
                            <option value="">Tous les niveaux</option>
                            <option value="nouveau-bachelier" <?php echo $niveau==='nouveau-bachelier'?'selected':''; ?>>Nouveau bachelier</option>
                            <option value="etudiant-bts" <?php echo $niveau==='etudiant-bts'?'selected':''; ?>>Étudiant en cycle BTS</option>
                            <option value="etudiant-licence" <?php echo $niveau==='etudiant-licence'?'selected':''; ?>>Étudiant en cycle licence</option>
                            <option value="etudiant-master" <?php echo $niveau==='etudiant-master'?'selected':''; ?>>Étudiant en cycle Master</option>
                            <option value="autre" <?php echo $niveau==='autre'?'selected':''; ?>>Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="filter-ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                        <select id="filter-ville" name="ville" class="px-4 py-2 w-full border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B80F6]">
                            <option value="">Toutes les villes</option>
                            <option value="douala" <?php echo $ville==='douala'?'selected':''; ?>>Douala</option>
                            <option value="yaounde" <?php echo $ville==='yaounde'?'selected':''; ?>>Yaoundé</option>
                            <option value="bafoussam" <?php echo $ville==='bafoussam'?'selected':''; ?>>Bafoussam</option>
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
        <!-- Les résultats seront chargés dynamiquement via JavaScript -->
        <div class="col-span-2 text-center py-10">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#2B80F6]"></div>
            <p class="text-gray-500 text-lg mt-2">Chargement des répétiteurs...</p>
            </div>
    </section>

    <div class="pagination-container">
        <!-- La pagination sera gérée dynamiquement via JavaScript -->
    </div>
    
    <script src="Scripts/script.js"></script>
    <script src="Scripts/tailwindcss.js"></script>
    <script src="Scripts/Recherche.js"></script>
</body>
</html>