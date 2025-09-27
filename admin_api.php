<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

// Connexion BD
$host = 'localhost';
$dbname = 'lekol';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur BD', 'details' => $e->getMessage()]);
    exit;
}

// Utilitaires
function json_ok($data = [], $message = 'OK') {
    echo json_encode(['success' => true, 'message' => $message, 'data' => $data]);
    exit;
}

function json_err($message = 'Erreur', $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

// Récupérer JSON si envoyé
$input = file_get_contents('php://input');
$json = null;
if ($input) {
    $tmp = json_decode($input, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $json = $tmp;
    }
}

// Actions publiques (accessibles sans connexion)
$public_actions = ['search_repetiteurs'];

// Vérification admin uniquement pour les actions non publiques
if (!in_array($action, $public_actions) && !isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

switch ($action) {
    // Tableau de bord: stats simples
    case 'get_stats': {
        $counts = [
            'repetiteurs' => (int)$pdo->query('SELECT COUNT(*) FROM repetiteur')->fetchColumn(),
            'parents' => (int)$pdo->query('SELECT COUNT(*) FROM parent')->fetchColumn(),
            'avis' => (int)$pdo->query('SELECT COUNT(*) FROM avis')->fetchColumn(),
        ];
        json_ok($counts);
    }

    // Répétiteurs: liste avec recherche/statut
    case 'list_repetiteurs': {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $statut = isset($_GET['statut']) ? trim($_GET['statut']) : '';
        $sql = 'SELECT id, nom, prenom, email, telephone, ville, niveau_cible, matieres, statut, 
                       piece_identite, certificat_scolarite, releve_bac, preuve_experience 
                FROM repetiteur';
        $where = [];
        $params = [];
        if ($search !== '') {
            $where[] = '(nom LIKE :q OR prenom LIKE :q OR email LIKE :q)';
            $params[':q'] = "%$search%";
        }
        if ($statut !== '' && $statut !== 'tous') {
            $where[] = 'statut = :statut';
            $params[':statut'] = $statut;
        }
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY id DESC LIMIT 500';
        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        json_ok(['items' => $rows]);
    }

    // Détails d'un répétiteur avec documents
    case 'get_repetiteur_details': {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) json_err('ID invalide');
        
        $sql = 'SELECT id, nom, prenom, email, telephone, ville, quartier, date_naissance, 
                       niveau_etudes, universite, filiere, matieres, niveau_cible, zones, description,
                       piece_identite, certificat_scolarite, releve_bac, preuve_experience, statut
                FROM repetiteur WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $repetiteur = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$repetiteur) json_err('Répétiteur non trouvé', 404);
        
        json_ok(['repetiteur' => $repetiteur]);
    }

    // Activités récentes (agrégées depuis plusieurs tables)
    case 'get_recent_activities': {
        $activities = [];

        // Derniers repetiteurs inscrits
        try {
            $stmt = $pdo->query("SELECT id, nom, prenom, email, NOW() AS ts FROM repetiteur ORDER BY id DESC LIMIT 5");
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $activities[] = [
                    'type' => 'inscription_repetiteur',
                    'name' => trim(($r['prenom'] ?? '') . ' ' . ($r['nom'] ?? '')) ?: ('Répétiteur #' . $r['id']),
                    'action' => 'Nouveau répétiteur inscrit',
                    'timestamp' => $r['ts']
                ];
            }
        } catch (Exception $e) { /* silencieux */ }

        // Derniers parents inscrits
        try {
            $stmt = $pdo->query("SELECT id, nom, prenom, email, NOW() AS ts FROM parent ORDER BY id DESC LIMIT 5");
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
                $activities[] = [
                    'type' => 'inscription_parent',
                    'name' => trim(($p['prenom'] ?? '') . ' ' . ($p['nom'] ?? '')) ?: ('Parent #' . $p['id']),
                    'action' => 'Nouveau parent inscrit',
                    'timestamp' => $p['ts']
                ];
            }
        } catch (Exception $e) { /* silencieux */ }

        // Derniers avis
        try {
            $stmt = $pdo->query("SELECT a.id, a.note, a.date_creation, p.prenom AS parent_prenom, p.nom AS parent_nom
                                  FROM avis a JOIN parent p ON p.id = a.parent_id
                                  ORDER BY a.id DESC LIMIT 5");
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $a) {
                $activities[] = [
                    'type' => 'avis',
                    'name' => trim(($a['parent_prenom'] ?? '') . ' ' . ($a['parent_nom'] ?? '')) ?: ('Parent'),
                    'action' => 'Nouvel avis ' . (int)$a['note'] . ' étoiles',
                    'timestamp' => $a['date_creation']
                ];
            }
        } catch (Exception $e) { /* silencieux */ }

        // Trier par timestamp desc si possible
        usort($activities, function($x, $y) {
            return strcmp($y['timestamp'], $x['timestamp']);
        });

        // Limiter à 12 éléments
        $activities = array_slice($activities, 0, 12);
        json_ok(['items' => $activities]);
    }

    // Répétiteurs: mise à jour statut
    case 'update_repetiteur_statut': {
        $id = (int)($json['id'] ?? $_POST['id'] ?? 0);
        $statut = trim($json['statut'] ?? $_POST['statut'] ?? '');
        if ($id <= 0 || $statut === '') json_err('Paramètres invalides');
        
        $stmt = $pdo->prepare('UPDATE repetiteur SET statut = :statut WHERE id = :id');
        $stmt->execute([':statut' => $statut, ':id' => $id]);
        json_ok([], 'Statut mis à jour avec succès');
    }

    // Répétiteurs: suppression
    case 'delete_repetiteur': {
        $id = (int)($json['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) json_err('ID invalide');
        $stmt = $pdo->prepare('DELETE FROM repetiteur WHERE id = :id');
        $stmt->execute([':id' => $id]);
        json_ok([], 'Répétiteur supprimé');
    }

    // Parents: liste simple
    case 'list_parents': {
        $stmt = $pdo->query('SELECT id, nom, prenom, email, telephone, ville, quartier, dateInscription FROM parent ORDER BY id DESC LIMIT 500');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        json_ok(['items' => $rows]);
    }

    // Avis: liste + filtre statut
    case 'list_avis': {
        $statut = isset($_GET['statut']) ? $_GET['statut'] : '';
        $sql = 'SELECT a.id, a.parent_id, a.repetiteur_id, a.note, a.commentaire, a.statut, a.date_creation, 
                       p.nom AS parent_nom, p.prenom AS parent_prenom,
                       r.nom AS rep_nom, r.prenom AS rep_prenom
                FROM avis a 
                JOIN parent p ON p.id = a.parent_id
                JOIN repetiteur r ON r.id = a.repetiteur_id';
        $params = [];
        if ($statut !== '') {
            $sql .= ' WHERE a.statut = :statut';
            $params[':statut'] = $statut;
        }
        $sql .= ' ORDER BY a.id DESC LIMIT 1000';
        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        json_ok(['items' => $rows]);
    }

    // Avis: mise à jour du statut
    case 'update_avis_statut': {
        $id = (int)($json['id'] ?? $_POST['id'] ?? 0);
        $statut = trim($json['statut'] ?? $_POST['statut'] ?? '');
        if ($id <= 0 || !in_array($statut, ['approuve','en_attente','rejete'], true)) {
            json_err('Paramètres invalides');
        }
        $stmt = $pdo->prepare('UPDATE avis SET statut = :statut WHERE id = :id');
        $stmt->execute([':statut' => $statut, ':id' => $id]);
        json_ok([], 'Statut de l\'avis mis à jour');
    }

    // Recherche répétiteurs (public - accessible sans session)
    case 'search_repetiteurs': {
        $search = trim($_GET['search'] ?? '');
        $matiere = trim($_GET['matiere'] ?? '');
        $niveau = trim($_GET['niveau'] ?? '');
        $ville = trim($_GET['ville'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT id, nom, prenom, matieres, niveau_cible, ville, description, photo_profil 
                FROM repetiteur 
                WHERE statut = 'actif'";
        $count_sql = "SELECT COUNT(*) as total FROM repetiteur WHERE statut = 'actif'";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (nom LIKE :search OR prenom LIKE :search OR matieres LIKE :search)";
            $count_sql .= " AND (nom LIKE :search OR prenom LIKE :search OR matieres LIKE :search)";
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
            $sql .= " AND ville LIKE :ville";
            $count_sql .= " AND ville LIKE :ville";
            $params[':ville'] = '%' . $ville . '%';
        }
        
        $sql .= " ORDER BY nom, prenom LIMIT :limit OFFSET :offset";
        
        // Calcul du total
        $stmt_count = $pdo->prepare($count_sql);
        foreach ($params as $key => $value) {
            $stmt_count->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt_count->execute();
        $total_result = $stmt_count->fetch(PDO::FETCH_ASSOC);
        $total = (int)($total_result['total'] ?? 0);
        
        // Récupération des résultats
        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $repetiteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total_pages = $total > 0 ? max(1, (int)ceil($total / $limit)) : 0;
        
        json_ok([
            'repetiteurs' => $repetiteurs,
            'total' => $total,
            'page' => $page,
            'total_pages' => $total_pages
        ]);
    }

    default:
        json_err('Action inconnue', 404);
}
?>