<?php
session_start();

// Si déjà connecté, aller au dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: Dashboard.php');
    exit;
}

$host = 'localhost';
$dbname = 'lekol';
$username = 'root';
$password = '';

$error = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la table administrateur si elle n'existe pas
    $pdo->exec("CREATE TABLE IF NOT EXISTS administrateur (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(190) NOT NULL UNIQUE,
        motDePasse VARCHAR(255) NOT NULL,
        role VARCHAR(50) DEFAULT 'admin',
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed: si aucun admin, créer un compte par défaut
    $count = (int)$pdo->query("SELECT COUNT(*) FROM administrateur")->fetchColumn();
    if ($count === 0) {
        $defaultEmail = 'admin@lekol.local';
        $defaultHash = password_hash('Admin123!', PASSWORD_DEFAULT);
        $stmtSeed = $pdo->prepare('INSERT INTO administrateur (email, motDePasse, role) VALUES (?, ?, ?)');
        $stmtSeed->execute([$defaultEmail, $defaultHash, 'admin']);
    } else {
        // Vérifier et corriger le hash du mot de passe par défaut si nécessaire
        $stmtCheck = $pdo->prepare('SELECT motDePasse FROM administrateur WHERE email = ?');
        $stmtCheck->execute(['admin@lekol.local']);
        $existingHash = $stmtCheck->fetchColumn();
        
        // Remplacer str_starts_with() par une alternative compatible PHP < 8.0
        if ($existingHash && substr($existingHash, 0, 4) !== '$2y$') {
            $correctHash = password_hash('Admin123!', PASSWORD_DEFAULT);
            $stmtUpdate = $pdo->prepare('UPDATE administrateur SET motDePasse = ? WHERE email = ?');
            $stmtUpdate->execute([$correctHash, 'admin@lekol.local']);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $pwd = isset($_POST['password']) ? $_POST['password'] : '';

        $stmt = $pdo->prepare('SELECT id, email, motDePasse FROM administrateur WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($pwd, $admin['motDePasse'])) {
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            header('Location: Dashboard.php');
            exit;
        } else {
            $error = "Identifiants invalides";
        }
    }
} catch (Exception $e) {
    $error = "Erreur serveur: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="src/css/output.css">
    <style>
        /* S'assure que le body est visible même si un style global le masque */
        body { visibility: visible !important; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        <form method="post" class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 space-y-6">
            <div class="space-y-1">
                <h2 class="text-2xl font-semibold text-gray-900">Connexion Administrateur</h2>
                <p class="text-sm text-gray-600">Accédez à votre tableau de bord sécurisé.</p>
            </div>

            <?php if (!empty($error)) echo "<div class='rounded-lg border border-red-200 bg-red-50 text-red-700 text-sm p-3'>" . htmlspecialchars($error) . "</div>"; ?>
            <?php if (isset($_GET['expired'])) echo "<div class='rounded-lg border border-yellow-200 bg-yellow-50 text-yellow-800 text-sm p-3'>Votre session a expiré. Veuillez vous reconnecter.</div>"; ?>

            <div class="space-y-1">
                <label class="text-sm text-gray-700">Email</label>
                <input type="email" name="email" placeholder="email@exemple.com" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-600 placeholder-gray-400">
            </div>

            <div class="space-y-1">
                <label class="text-sm text-gray-700">Mot de passe</label>
                <input type="password" name="password" placeholder="Votre mot de passe" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-600 placeholder-gray-400">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white h-11 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">Se connecter</button>

            <p class="text-xs text-gray-500 text-center">Par défaut: <span class="font-medium">admin@lekol.local</span> / <span class="font-medium">Admin123!</span></p>
        </form>
    </div>

    <script src="Scripts/tailwindcss.js"></script>
</body>
</html>