<?php
session_start();

// Vérifier si les données de récapitulatif sont présentes
if (!isset($_SESSION['recapitulatif_data'])) {
    header("Location: InscriptionRepetiteur.php");
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

$data = $_SESSION['recapitulatif_data'];

// Uploads des fichiers
$uploads_dir = "uploads/";
if (!is_dir($uploads_dir)) mkdir($uploads_dir);

function uploadFile($input, $uploads_dir) {
    if (!empty($_FILES[$input]['name'])) {
        $filename = uniqid() . "_" . basename($_FILES[$input]['name']);
        $path = $uploads_dir . $filename;
        move_uploaded_file($_FILES[$input]['tmp_name'], $path);
        return $path;
    }
    return null;
}

// Traitement des fichiers uploadés
$identite = uploadFile("identite", $uploads_dir);
$certificat = uploadFile("certificat", $uploads_dir);
$releve = uploadFile("releve", $uploads_dir);
$preuve = uploadFile("preuve", $uploads_dir);

// Si les fichiers n'ont pas été uploadés dans cette session, utiliser les chemins stockés
if (!$identite && isset($data['identite'])) {
    $identite = $data['identite'];
}
if (!$certificat && isset($data['certificat'])) {
    $certificat = $data['certificat'];
}
if (!$releve && isset($data['releve'])) {
    $releve = $data['releve'];
}
if (!$preuve && isset($data['preuve'])) {
    $preuve = $data['preuve'];
}

// Hash du mot de passe
$mot_de_passe = password_hash($data['motDePasse'], PASSWORD_BCRYPT);

// Insertion en base de données
$sql = "INSERT INTO repetiteur (email, motDePasse, nom, prenom, telephone, ville, quartier, date_naissance, 
        niveau_etudes, universite, filiere, matieres, niveau_cible, zones, description, photo_profil,
        piece_identite, certificat_scolarite, releve_bac, preuve_experience, statut) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $pdo->prepare($sql);
$ok = $stmt->execute([
    $data['email'], $mot_de_passe, $data['nom'], $data['prenom'], $data['telephone'], 
    $data['ville'], $data['quartier'], $data['dateNaissance'], $data['niveau'], 
    $data['universite'], $data['filiere'], $data['matiere'], $data['niveauCible'], 
    $data['zone'], $data['description'], null, // photo_profil sera ajoutée plus tard
    $identite, $certificat, $releve, $preuve, 'actif'
]);

if ($ok) {
    // Créer la session utilisateur
    $_SESSION['id'] = $pdo->lastInsertId();
    $_SESSION['nom'] = $data['nom'];
    $_SESSION['prenom'] = $data['prenom'];
    $_SESSION['type_utilisateur'] = 'repetiteur';
    
    // Nettoyer les données de récapitulatif
    unset($_SESSION['recapitulatif_data']);
    
    // Redirection vers la page d'accueil
    header("Location: index2.php");
    exit;
} else {
    // En cas d'erreur, rediriger vers le formulaire
    unset($_SESSION['recapitulatif_data']);
    header("Location: InscriptionRepetiteur.php?error=1");
    exit;
}
?>
