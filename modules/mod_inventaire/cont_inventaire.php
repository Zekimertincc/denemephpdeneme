<?php
require_once 'modele_inventaire.php';
require_once 'vue_inventaire.php';
require_once __DIR__ . '/../../composants/auth.php';

class ContInventaire {
    private $modele;
    private $vue;

    public function __construct() {
        requireActive();
        ensureAssociationSelected($_SESSION['id_user']);
        requireRole(['gestionnaire', 'admin']);
        $this->modele = new ModeleInventaire();
        $this->vue = new VueInventaire();
    }

    public function exec_action() {
        $idAsso = $_SESSION['idAsso'];
        $inventaire = $this->modele->getInventaireComplet($idAsso);
        $nbAlertes = $this->modele->getStatsAlerte($idAsso);
        
        $this->vue->afficherInventaire($inventaire, $nbAlertes);
        $this->vue->afficher();
    }
}
