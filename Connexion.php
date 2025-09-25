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

if(isset($_POST['ok'])) {
    $email = trim($_POST['email']);
    $motDePasse = trim($_POST['motDePasse']);

    if(empty($email) || empty($motDePasse)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        // Tableau des tables à tester
        $tables = [
            'parent' => 'parent',
            'repetiteur' => 'repetiteur'
        ];

        $connecte = false;

        foreach($tables as $type => $table) {
            $stmt = $conn->prepare("SELECT * FROM $table WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($motDePasse, $user['motDePasse'])) {
                // Connexion réussie
                $_SESSION['id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['type_utilisateur'] = $type;

                if($user['email'] === 'tiomenecabrel@gmail.com') {
                    header("Location: Dashboard.php");
                } else {
                    header("Location: index2.php");
                }
                exit();
            }
        }

        // Si on sort de la boucle, aucun utilisateur trouvé
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Charger style.css en dernier pour forcer Poppins -->
    <link rel="stylesheet" href="style.css">
</head>
<body class="flex justify-center items-center min-h-screen bg-gray-50">
    
     <form method="POST" class="flex bg-white shadow-lg flex-col gap-5 my-10 h-auto w-auto p-10 rounded-xl px-4">
        <h1 class="text-6xl text-[#2B80F6] font-bold mb-5">Connexion</h1>

        <?php if(isset($erreur)) : ?>
            <p class="text-red-500 font-semibold"><?php echo $erreur; ?></p>
        <?php endif; ?>

        <div class="flex flex-col gap-5 mb-5">
            <div>
                <input class="border-[1px] w-full bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" name="email" type="email" placeholder="nom d'utilisateur ou e-mail" required>
                <p class="text-red-500 text-sm mt-1" id="emailError"></p>
            </div>
            <input class="border-[1px] bg-transparent border-[#2B80F6] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-[#2B80F6]" type="password" name="motDePasse" placeholder="Mot de passe">
        </div>
        <input type="submit" name="ok" class="bg-[#2B80F6] p-2 px-5  rounded-lg text-white md:block shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-[#2B80F6]-400/50" value="Se connecter">

       <p class="relative text-gray-500">Vous n'avez pas de compte ? <a class="text-[#2B80F6]" href="Choix.html">S'inscrire</a></p>
    </form>

    <script src="Scripts/form.js"></script>
    <script src="Scripts/script.js"></script>
    <script src="Scripts/tailwindcss.js"></script>
</body>
</html>