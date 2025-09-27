<?php
session_start();

// Vérifier si les données de récapitulatif sont présentes
if (!isset($_SESSION['recapitulatif_data'])) {
    echo "<h1>Erreur</h1>";
    echo "<p>Aucune donnée de récapitulatif trouvée.</p>";
    echo "<a href='InscriptionRepetiteur.php'>Retour au formulaire</a>";
    exit;
}

$data = $_SESSION['recapitulatif_data'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif - Inscription Répétiteur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: #f5f5f5; 
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            overflow: hidden;
        }
        .header { 
            background: #2B80F6; 
            color: white; 
            padding: 30px; 
            text-align: center; 
        }
        .content { 
            padding: 30px; 
        }
        .section { 
            margin-bottom: 30px; 
            padding: 20px; 
            background: #f8f9fa; 
            border-radius: 8px; 
        }
        .section h2 { 
            color: #2B80F6; 
            margin-bottom: 20px; 
            display: flex; 
            align-items: center; 
        }
        .field { 
            margin-bottom: 15px; 
        }
        .field label { 
            font-weight: bold; 
            color: #333; 
            display: block; 
            margin-bottom: 5px; 
        }
        .field .value { 
            background: white; 
            padding: 10px; 
            border-radius: 5px; 
            border: 1px solid #ddd; 
        }
        .grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 20px; 
        }
        .document { 
            background: white; 
            padding: 15px; 
            border-radius: 5px; 
            border: 1px solid #ddd; 
            margin-bottom: 10px; 
        }
        .document h4 { 
            margin: 0 0 10px 0; 
            color: #333; 
        }
        .status { 
            padding: 5px 10px; 
            border-radius: 15px; 
            font-size: 12px; 
            font-weight: bold; 
        }
        .status.success { 
            background: #d4edda; 
            color: #155724; 
        }
        .status.warning { 
            background: #fff3cd; 
            color: #856404; 
        }
        .status.danger { 
            background: #f8d7da; 
            color: #721c24; 
        }
        .actions { 
            text-align: center; 
            padding: 30px; 
            background: #f8f9fa; 
        }
        .btn { 
            display: inline-block; 
            padding: 12px 24px; 
            margin: 0 10px; 
            text-decoration: none; 
            border-radius: 5px; 
            font-weight: bold; 
            transition: all 0.3s; 
        }
        .btn-secondary { 
            background: #6c757d; 
            color: white; 
        }
        .btn-primary { 
            background: #2B80F6; 
            color: white; 
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
        }
        @media (max-width: 768px) {
            .grid { 
                grid-template-columns: 1fr; 
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-check-circle"></i> Récapitulatif de votre inscription</h1>
            <p>Vérifiez vos informations avant de finaliser votre inscription</p>
        </div>

        <!-- Contenu -->
        <div class="content">
            <!-- Informations personnelles -->
            <div class="section">
                <h2><i class="fas fa-user"></i> Informations personnelles</h2>
                <div class="grid">
                    <div class="field">
                        <label>Nom complet</label>
                        <div class="value"><?php echo htmlspecialchars($data['nom'] . ' ' . $data['prenom']); ?></div>
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <div class="value"><?php echo htmlspecialchars($data['email']); ?></div>
                    </div>
                    <div class="field">
                        <label>Téléphone</label>
                        <div class="value"><?php echo htmlspecialchars($data['telephone']); ?></div>
                    </div>
                    <div class="field">
                        <label>Date de naissance</label>
                        <div class="value"><?php echo htmlspecialchars($data['dateNaissance']); ?></div>
                    </div>
                    <div class="field">
                        <label>Ville</label>
                        <div class="value"><?php echo htmlspecialchars($data['ville']); ?></div>
                    </div>
                    <div class="field">
                        <label>Quartier</label>
                        <div class="value"><?php echo htmlspecialchars($data['quartier']); ?></div>
                    </div>
                </div>
            </div>

            <!-- Informations académiques -->
            <div class="section">
                <h2><i class="fas fa-graduation-cap"></i> Informations académiques</h2>
                <div class="grid">
                    <div class="field">
                        <label>Niveau d'études</label>
                        <div class="value"><?php echo htmlspecialchars($data['niveau'] ?: 'Non spécifié'); ?></div>
                    </div>
                    <div class="field">
                        <label>Université</label>
                        <div class="value"><?php echo htmlspecialchars($data['universite']); ?></div>
                    </div>
                    <div class="field">
                        <label>Filière</label>
                        <div class="value"><?php echo htmlspecialchars($data['filiere']); ?></div>
                    </div>
                    <div class="field">
                        <label>Niveau scolaire cible</label>
                        <div class="value"><?php echo htmlspecialchars($data['niveauCible'] ?: 'Non spécifié'); ?></div>
                    </div>
                </div>
                <div class="field">
                    <label>Matières enseignées</label>
                    <div class="value"><?php echo htmlspecialchars($data['matiere']); ?></div>
                </div>
                <div class="field">
                    <label>Zones de déplacement</label>
                    <div class="value"><?php echo htmlspecialchars($data['zone']); ?></div>
                </div>
                <div class="field">
                    <label>Description</label>
                    <div class="value"><?php echo htmlspecialchars($data['description']); ?></div>
                </div>
            </div>

            <!-- Documents -->
            <div class="section">
                <h2><i class="fas fa-file-alt"></i> Documents soumis</h2>
                <div class="grid">
                    <div class="document">
                        <h4>Pièce d'identité</h4>
                        <?php if (!empty($data['identite'])): ?>
                            <span class="status success">✓ Document fourni</span>
                        <?php else: ?>
                            <span class="status danger">✗ Document manquant</span>
                        <?php endif; ?>
                    </div>
                    <div class="document">
                        <h4>Certificat de scolarité</h4>
                        <?php if (!empty($data['certificat'])): ?>
                            <span class="status success">✓ Document fourni</span>
                        <?php else: ?>
                            <span class="status danger">✗ Document manquant</span>
                        <?php endif; ?>
                    </div>
                    <div class="document">
                        <h4>Relevé de notes BAC</h4>
                        <?php if (!empty($data['releve'])): ?>
                            <span class="status success">✓ Document fourni</span>
                        <?php else: ?>
                            <span class="status danger">✗ Document manquant</span>
                        <?php endif; ?>
                    </div>
                    <div class="document">
                        <h4>Preuve d'expérience</h4>
                        <?php if (!empty($data['preuve'])): ?>
                            <span class="status success">✓ Document fourni</span>
                        <?php else: ?>
                            <span class="status warning">○ Optionnel - Non fourni</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="InscriptionRepetiteur.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au formulaire
            </a>
            <form method="POST" action="finaliser_inscription.php" style="display: inline;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Confirmer l'inscription
                </button>
            </form>
        </div>
    </div>
</body>
</html>
