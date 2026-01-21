<?php
session_start();

if (!isset($_SESSION['idAsso'])) {
    $_SESSION['idAsso'] = 1;
}

require_once 'Connexion.php';
require_once 'VueGenerique.php';
require_once 'composants/menu/ControllerMenu.php';
require_once 'composants/footer/ControllerFooter.php';

Connexion::initConnexion();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>E-BUVETTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="public/css/app.css" />
</head>
<body>
<main class="container py-5 app-container">
    <header class="header-bar d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="brand-title">E-BUVETTE</div>
        <?php
        $contM = new ControllerMenu();
        $contM->afficherMenu();
        ?>
    </header>

    <?php
    $module = isset($_GET['module']) ? $_GET['module'] : "default";

    switch ($module) {
        case 'produit':
            include_once 'modules/mod_produit/mod_produit.php';
            new ModProduit();
            break;
        case 'connexion':
            include_once 'modules/mod_connexion/mod_connexion.php';
            new ModConnexion();
            break;
        case 'solde':
            include_once 'modules/mod_solde/mod_solde.php';
            new ModSolde();
            break;
        case 'barman': 
            include_once 'modules/mod_barman/mod_barman.php';
            new ModBarman();
            break;
        case 'inscription':
            include_once 'modules/mod_inscription/mod_inscription.php';
            new ModInscription();
            break;
        case 'inventaire':
            include_once 'modules/mod_inventaire/mod_inventaire.php';
            new ModInventaire();
            break;
        case 'profil':
            include_once 'modules/mod_profil/mod_profil.php';
            new ModProfil();
            break;
        case 'historique':
            include_once 'modules/mod_historique/mod_historique.php';
            new ModHistorique();
            break;
        case 'fournisseur':
            include_once 'modules/mod_fournisseur/mod_fournisseur.php';
            new ModFournisseur();
            break;
        default:
            echo '<div class="card-soft p-4"><p class="mb-0">Bienvenue sur le site. Sélectionnez un module dans le menu pour commencer.</p></div>';
            break;
    }
    ?>
</main>

<footer class="mt-5">
    <?php
    $contF = new ControllerFooter();
    $contF->afficherFooter();
    ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>