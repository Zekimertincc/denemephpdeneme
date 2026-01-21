<?php
require_once 'modele_produit.php';
require_once 'vue_produit.php';
require_once __DIR__ . '/../../composants/auth.php';

class ContProduit
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        requireActive();
        ensureAssociationSelected($_SESSION['id_user']);
        requireRole(['gestionnaire', 'admin']);

        $this->action = isset($_GET['action']) ? $_GET['action'] : 'liste';
        $this->modele = new ModeleProduit();
        $this->vue = new VueProduit();
    }

    public function exec_action()
    {
        switch ($this->action) {
            case 'liste':
                $this->liste();
                break;
            case 'details':
                if (isset($_GET['id'])) {
                    $this->details($_GET['id']);
                } else {
                    echo "Aucun ID de produit fourni.";
                }
                break;
            case 'ajout':
                $this->ajout();
                break;
            default:
                $this->liste();
        }
    }

    public function liste()
    {
        $idAsso = $_SESSION['idAsso'];
        $produits = $this->modele->getListe($idAsso);
        $this->vue->affiche_liste($produits);
        $this->vue->afficher();
    }

    public function details($id)
    {
        $produit = $this->modele->getDetails($id);
        $this->vue->affiche_details($produit);
        $this->vue->afficher();
    }

    public function ajout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->modele->ajoutProduit($_POST, $_SESSION['idAsso']);
            header('Location: index.php?module=produit&action=liste');
            exit;
        } else {
            $this->vue->form_ajout();
        }
        $this->vue->afficher();
    }
}
