<?php
session_start();

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


if (isset($_POST['ok'])) {
    
    // Récupérer les champs texte
    $email = $_POST['email'];
    $mot_de_passe = $_POST['motDePasse']; // Ne pas hasher maintenant
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $ville = $_POST['ville'];
    $quartier = $_POST['quartier'];
    $dateNaissance = $_POST['dateNaissance'];
    $niveau = $_POST['niveau'];
    $universite = $_POST['universite'];
    $filiere = $_POST['filiere'];
    $matiere = $_POST['matiere'];
    $niveauCible = $_POST['niveauCible'];
    $zone = $_POST['zone'];
    $description = $_POST['description'];

    // Uploads
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

    $identite = uploadFile("identite", $uploads_dir);
    $certificat = uploadFile("certificat", $uploads_dir);
    $releve = uploadFile("releve", $uploads_dir);
    $preuve = uploadFile("preuve", $uploads_dir);

    // Stocker les données en session pour le récapitulatif
    $_SESSION['recapitulatif_data'] = [
        'email' => $email,
        'motDePasse' => $mot_de_passe,
        'nom' => $nom,
        'prenom' => $prenom,
        'telephone' => $telephone,
        'ville' => $ville,
        'quartier' => $quartier,
        'dateNaissance' => $dateNaissance,
        'niveau' => $niveau,
        'universite' => $universite,
        'filiere' => $filiere,
        'matiere' => $matiere,
        'niveauCible' => $niveauCible,
        'zone' => $zone,
        'description' => $description,
        'identite' => $identite,
        'certificat' => $certificat,
        'releve' => $releve,
        'preuve' => $preuve
    ];

    // Rediriger vers la page de récapitulatif finale
    header("Location: RecapitulatifInscription_final.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Répétiteur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="flex justify-center items-center">
    <form method="post" action="" enctype="multipart/form-data" class="flex shadow-lg flex-col gap-5 my-10 h-auto w-auto p-10 rounded-xl px-4 mx-4" id="inscriptionForm" onsubmit="return validateForm()">
        <h1 class="text-6xl text-[#2B80F6] font-bold mb-5">Inscription</h1>

        <div class="flex flex-col gap-5 mb-5">
            <div>
                <input class="border-[1px] w-full bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="email" type="email" placeholder="Adresse e-mail" required>
                <p class="text-red-500 text-sm mt-1" id="emailError"></p>
            </div>
            <div>
                <input class="border-[1px] w-full bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="motDePasse" type="password" placeholder="Mot de passe" required>
                <p class="text-red-500 text-sm mt-1" id="passwordError"></p>
            </div>
            <div>
                <input class="border-[1px] w-full bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="confirm" type="password" placeholder="Confirmer le mot de passe" required>
                <p class="text-red-500 text-sm mt-1 hidden" id="confirmError"></p>
            </div>
            <input class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="nom" type="text" placeholder="Nom(s)" required>
            <input class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="prenom" type="text" placeholder="Prénom(s)" required>
            <div>
                <input class="border-[1px] w-full bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="telephone" type="tel" placeholder="Telephone (Ex: +237 694210071)" required>
                <p class="text-red-500 text-sm mt-1 hidden" id="phoneError"></p>
            </div>
            <input class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="ville" type="text" placeholder="Ville de residence" required>
            <input class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="quartier" type="texte" placeholder="Quartier de residence" required>

            <label class="relative -mt-2 -mb-5 left-3 text-[13px]" for="">Date de naissance</label>
            <input class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6] text-gray-400" name="dateNaissance" type="date" placeholder="Date de naissance (JJ/MM/AAAA)">

            <select class="border-[1px] bg-transparent text-gray-400 border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="niveau" id="">
                <option class="text-gray-400 selected disabled hidden" value="">Niveau d'etudes</option>
                <option class="text-gray-400" value="">Nouveau bachelier</option>
                <option class="text-gray-400" value="">Etudiant en cycle BTS</option>
                <option class="text-gray-400" value="">Etudiant en cycle licence</option>
                <option class="text-gray-400" value="">Etudiant en cycle Master</option>
                <option class="text-gray-400"  value="">Autre</option>
            </select>

            <input class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="universite" type="text" placeholder="Université">
            <input class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="filiere" type="text" placeholder="Filiere d'étude">
            <input type="text" class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="matiere" placeholder="Matières enseignées (séparées par des virgules)">
            <select class="border-[1px] bg-transparent text-gray-400 border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="niveauCible" id="">
                <option class="text-gray-400 selected disabled hidden" value="">Niveau scolaire cible</option>
                <option class="text-gray-400" value="">Primaire</option>
                <option class="text-gray-400" value="">Secondaire</option>
            </select>
            <input type="text" class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="zone" placeholder="Zones de déplacement (quartiers)">
            <input type="text" class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="description" placeholder="Breve description de vous-même">

            <label class="relative -mt-2 -mb-5 left-3 text-[13px]" for="">Piece d'identité</label>
            <input type="file" class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="identite" placeholder="Photo d'identité">

            <label class="relative -mt-2 -mb-5 left-3 text-[13px]" for="">Preuve de Statut Étudiant(Certifcat de scolarite,...)</label>
            <input type="file" class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="certificat" placeholder="Certificat de scolarité">

            <label class="relative -mt-2 -mb-5 left-3 text-[13px]" for="">Releve de notes du baccalaureat</label>
            <input type="file" class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="releve" placeholder="Releve du BAC">

            <label class="relative -mt-2 -mb-5 left-3 text-[13px]" for="">Preuve d'Expérience (Optionnel)</label>
            <input type="file" class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="preuve" placeholder="Preuve d'Expérience">
            
            
        <input type="submit" name="ok" class="bg-[#2B80F6] p-2 px-5  rounded-lg text-white md:block shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-[#2B80F6]-400/50" value="S'inscrire">

        <p class="relative text-gray-500">Vous avez déjà un compte ? <a class="text-[#2B80F6]" href="Connexion.html">Se connecter</a></p>
    </form>




    <script src="Scripts/form.js"></script>
    <script src="Scripts/tailwindcss.js"></script>
    <script src="Scripts/script.js"></script>
</body>
</html>