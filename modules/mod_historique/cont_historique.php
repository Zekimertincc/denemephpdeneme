<?php
require_once 'modele_historique.php';
require_once 'vue_historique.php';

class ContHistorique {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        if (!isset($_SESSION['idAsso'])) {
            header('Location: index.php?module=connexion&action=choix_asso');
            exit;
        }
        $this->modele = new ModeleHistorique();
        $this->vue = new VueHistorique();
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'liste';
    }

    public function exec_action() {
        switch ($this->action) {
            case 'liste':
                $this->liste();
                break;
            case 'details':
                if (isset($_GET['id'])) {
                    $this->details($_GET['id']);
                }
                break;
            default:
                $this->liste();
                break;
        }
    }

    private function liste() {
        $idAsso = $_SESSION['idAsso'];
        $commandes = $this->modele->getHistoriqueComplet($idAsso);
        $recharges = $this->modele->getHistoriqueRecharges($idAsso);
        
        $this->vue->afficherHistorique($commandes, $recharges);
        $this->vue->afficher();
    }

    private function details($idCommande) {
        $details = $this->modele->getDetailsCommande($idCommande);
        $this->vue->afficherDetails($details);
        $this->vue->afficher();
    }
}