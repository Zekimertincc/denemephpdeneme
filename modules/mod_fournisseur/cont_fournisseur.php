<?php
require_once 'modele_fournisseur.php';
require_once 'vue_fournisseur.php';
require_once __DIR__ . '/../../composants/auth.php';

class ContFournisseur {
    private $modele;
    private $vue;

    public function __construct() {
        requireActive();
        ensureAssociationSelected($_SESSION['id_user']);
        requireRole(['gestionnaire', 'admin']);
        $this->modele = new ModeleFournisseur();
        $this->vue = new VueFournisseur();
    }

    public function exec_action() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'liste';

        switch ($action) {
            case 'liste':
                $fours = $this->modele->getFournisseurs($_SESSION['idAsso']);
                $this->vue->afficherListe($fours);
                break;
            case 'formulaire_ajout':
                $this->vue->formulaireAjout();
                break;
            case 'valider_ajout':
                $this->modele->ajouterFournisseur($_POST['nom'], $_POST['contact'], $_POST['adresse'], $_SESSION['idAsso']);
                header("Location: index.php?module=fournisseur&action=liste");
                exit;
            case 'passer_commande':
                $fours = $this->modele->getFournisseurs($_SESSION['idAsso']);
                $prods = $this->modele->getProduits($_SESSION['idAsso']);
                $this->vue->formulaireCommande($fours, $prods);
                break;
            case 'valider_commande':
                $idProduit = $_POST['idProduit'];
                $quantite = $_POST['quantite'];
                $idFournisseur = $_POST['idFournisseur'];
                $prixTotal = $_POST['prixTotal'];
                $idAsso = $_SESSION['idAsso'];

                $this->modele->mettreAJourStock($idProduit, $quantite, $idAsso, $idFournisseur, $prixTotal);
                
                header("Location: index.php?module=fournisseur&action=liste");
                exit;
        }
        $this->vue->afficher();
    }
}
