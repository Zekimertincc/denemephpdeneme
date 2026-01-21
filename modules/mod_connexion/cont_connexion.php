<?php
include_once "modele_connexion.php";
include_once "vue_connexion.php";

class ContConnexion {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleConnexion();
        $this->vue = new VueConnexion();
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'formulaire';
    }

    public function exec_action() {
        switch ($this->action) {
            case 'formulaire':
                $this->vue->liste();
                break;
            case 'connexion':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $this->modele->connexion();
                } else {
                    $this->vue->afficherFormulaireConnexion();
                }
                break;
            case 'choix_asso':
                if (isset($_SESSION['login'])) {
                    $assos = $this->modele->getListeAssociations();
                    $this->vue->afficherChoixAsso($assos);
                } else {
                    header('Location: index.php?module=connexion&action=connexion');
                }
                break;
            case 'valider_choix':
                if (isset($_GET['id'])) {
                    $_SESSION['idAsso'] = $_GET['id'];
                    header('Location: index.php');
                    exit;
                }
                break;
            case 'deconnexion':
                $this->modele->deconnexion();
                break;
        }
        $this->vue->afficher();
    }
}