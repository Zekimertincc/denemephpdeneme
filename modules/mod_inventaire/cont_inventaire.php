<?php
require_once 'modele_inventaire.php';
require_once 'vue_inventaire.php';

class ContInventaire {
    private $modele;
    private $vue;

    public function __construct() {
        if (!isset($_SESSION['idAsso'])) {
            header('Location: index.php?module=connexion&action=choix_asso');
            exit;
        }
        $this->modele = new ModeleInventaire();
        $this->vue = new VueInventaire();
    }

    public function exec_action() {
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'client';

        if ($role !== 'admin' && $role !== 'gestionnaire') {
            header('Location: index.php?module=barman&action=caisse');
            exit;
        }

        $idAsso = $_SESSION['idAsso'];
        $inventaire = $this->modele->getInventaireComplet($idAsso);
        $nbAlertes = $this->modele->getStatsAlerte($idAsso);
        
        $this->vue->afficherInventaire($inventaire, $nbAlertes);
        $this->vue->afficher();
    }
}