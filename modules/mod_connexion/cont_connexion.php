<?php
include_once "modele_connexion.php";
include_once "vue_connexion.php";
require_once __DIR__ . '/../../composants/auth.php';

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
                    $selection = resolveAssociationSelection($_SESSION['id_user']);
                    if ($selection['status'] === 'choose') {
                        $this->vue->afficherChoixAsso($selection['associations']);
                    } elseif ($selection['status'] === 'assigned') {
                        header('Location: index.php');
                        exit;
                    } else {
                        $_SESSION['flash_error'] = "Aucune association liée à votre compte.";
                        header('Location: index.php');
                        exit;
                    }
                } else {
                    header('Location: index.php?module=connexion&action=connexion');
                }
                break;
            case 'valider_choix':
                if (isset($_GET['id'], $_SESSION['id_user'])) {
                    $assos = $this->modele->getListeAssociations($_SESSION['id_user']);
                    $idChoisi = $_GET['id'];
                    $idsAutorises = array_column($assos, 'idAsso');

                    if (in_array($idChoisi, $idsAutorises, false)) {
                        $_SESSION['idAsso'] = $idChoisi;
                        header('Location: index.php');
                        exit;
                    }
                }
                $_SESSION['flash_error'] = "Association invalide.";
                header('Location: index.php');
                exit;
                break;
            case 'deconnexion':
                $this->modele->deconnexion();
                break;
        }
        $this->vue->afficher();
    }
}
