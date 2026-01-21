<?php
require_once 'modele_inscription.php';
require_once 'vue_inscription.php';

class ContInscription {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleInscription();
        $this->vue = new VueInscription();
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'formulaire';
    }

    public function exec_action() {
        switch ($this->action) {
            case 'formulaire':
                $this->vue->afficherFormulaire();
                break;
            case 'valider':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $res = $this->modele->inscrireUtilisateur($_POST);
                    $this->vue->afficherResultat($res);
                }
                break;
        }
        $this->vue->afficher();
    }
}