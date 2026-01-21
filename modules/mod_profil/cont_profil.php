<?php
require_once __DIR__ . '/modele_profil.php';
require_once __DIR__ . '/vue_profil.php';
require_once __DIR__ . '/../../composants/auth.php';

class ContProfil {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleProfil();
        $this->vue = new VueProfil();
    }

    public function exec_action() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'voir';
        $login = isset($_SESSION['login']) ? $_SESSION['login'] : null;

        requireActive();

        switch ($action) {
            case 'voir':
                $user = $this->modele->getInfosUser($login);
                $this->vue->afficherProfil($user);
                break;
            case 'sauvegarder':
                $res = $this->modele->modifierInfos($login, $_POST['nom'], $_POST['prenom'], $_POST['email']);
                $this->vue->afficherMessage($res);
                break;
        }
        $this->vue->afficher();
    }
}
