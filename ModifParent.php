<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
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

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}

$idParent = $_SESSION['id'];

// Récupérer les infos actuelles
$stmt = $conn->prepare("SELECT * FROM parent WHERE id = :id");
$stmt->execute(['id' => $idParent]);
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$parent) {
    die("Parent introuvable.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $ville = trim($_POST['ville']);
    $quartier = trim($_POST['quartier']);

    if (!empty($nom) && !empty($prenom) && !empty($email)) {
        $stmt = $conn->prepare("UPDATE parent SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, ville = :ville, quartier = :quartier WHERE id = :id");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone,
            'ville' => $ville,
            'quartier' => $quartier,
            'id' => $idParent
        ]);

        // Mettre à jour la session
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['telephone'] = $telephone;
        $_SESSION['ville'] = $ville;
        $_SESSION['quartier'] = $quartier;

        $success = "Informations mises à jour avec succès !";
        header("Location: ProfilParent.php");
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mes informations</title>
    <link rel="stylesheet" href="style.css">
    <script src="Scripts/tailwindcss.js"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Modifier mes informations</h2>

        <?php if (isset($erreur)): ?>
            <p class="text-red-500 mb-2"><?php echo $erreur; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="text-green-500 mb-2"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <input type="text" name="nom" placeholder="Nom" class="w-full border p-2 rounded">
            <input type="text" name="prenom" placeholder="Prénom" class="w-full border p-2 rounded">
            <input type="email" name="email" placeholder="Email" class="w-full border p-2 rounded">
            <input type="phone" name="telephone" placeholder="Telephone" class="w-full border p-2 rounded">
            <input type="text" name="ville" placeholder="Ville" class="w-full border p-2 rounded">
            <input type="text" name="quartier" placeholder="Quartier" class="w-full border p-2 rounded">

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800">
                Mettre à jour
            </button>
        </form>
    </div>

</body>
</html>
