<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lekol";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$erreur = "";
if(isset($_POST['ok'])){
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['motDePasse']);
    $telephone = trim($_POST['telephone']);
    $ville = trim($_POST['ville']);
    $quartier = trim($_POST['quartier']);
    $dateInscription = date("Y-m-d");

    // Vérifier si l'email existe déjà
    $recupUser = $conn->prepare("SELECT * FROM parent WHERE email = :email");
    $recupUser->execute(array('email' => $email));
    $user = $recupUser->fetch();

    if($user){
        $erreur = "Ce compte existe deja !!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $requete = $conn->prepare("INSERT INTO parent (nom, prenom, email, motDePasse, telephone, ville, quartier, dateInscription) 
                                   VALUES (:nom, :prenom, :email, :motDePasse, :telephone, :ville, :quartier, :dateInscription)");
        $requete->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'motDePasse' => $hashedPassword,
            'telephone' => $telephone,
            'ville' => $ville,
            'quartier' => $quartier,
            'dateInscription' => $dateInscription
        ]);

        $_SESSION['id'] = $conn->lastInsertId();
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['type_utilisateur'] = 'parent';

        header("Location: index2.php");
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Parent</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="flex justify-center items-center">
    <form method="post" class="flex shadow-lg flex-col gap-5 my-10 h-auto w-auto p-10 rounded-xl px-4" id="inscriptionForm" onsubmit="return validateForm()">
    <h1 class="text-6xl text-[#2B80F6] font-bold mb-5">Inscription</h1>

    <?php if(!empty($erreur)) : ?>
        <p class="text-red-500 font-semibold"><?php echo $erreur; ?></p>
    <?php endif; ?>

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
        <input class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="quartier" type="texte" placeholder="quartier de residence" required>
    </div>
    <input type="submit" name="ok" class="bg-[#2B80F6] p-2 px-5  rounded-lg text-white md:block shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-[#2B80F6]-400/50" value="S'inscrire">

    <p class="relative text-gray-500">Vous avez déjà un compte ? <a class="text-[#2B80F6]" href="Connexion.php">Se connecter</a></p>
</form>

    <script src="Scripts/form.js"></script>
    <script src="Scripts/tailwindcss.js"></script>
    <script src="Scripts/script.js"></script>
</body>
</html>