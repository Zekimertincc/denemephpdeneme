<?php
include_once "modele_solde.php";
include_once "vue_solde.php";
require_once __DIR__ . '/../../composants/auth.php';

class ContSolde {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        requireActive();
        ensureAssociationSelected($_SESSION['id_user']);
        $this->modele = new ModeleSolde();
        $this->vue = new VueSolde();
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'formulaire';
    }

    public function exec_action() {
        switch ($this->action) {
            case 'formulaire':
                $clients = $this->modele->getListeClients();
                $this->vue->afficherFormulaireRecharge($clients);
                break;
            case 'valider':
                $idClient = $_POST['idClient'] ?? null;
                $montant = $_POST['montant'] ?? 0;
                $methode = $_POST['methode'] ?? 'Espèces';

                if ($idClient && $montant > 0) {
                    $res = $this->modele->rechargerCompte($idClient, $montant, $methode);
                    $this->vue->afficherConfirmation($res);
                } else {
                    $this->vue->afficherConfirmation(false);
                }
                break;
        }
        $this->vue->afficher();
    }
}
