<?php
require_once 'modele_barman.php';
require_once 'vue_barman.php';

class ContBarman {
    private $modele;
    private $vue;

    public function __construct() {
        if (!isset($_SESSION['idAsso'])) {
            header('Location: index.php?module=connexion&action=choix_asso');
            exit;
        }
        $this->modele = new ModeleBarman();
        $this->vue = new VueBarman();
    }

    public function exec_action() {
        $idAsso = $_SESSION['idAsso'];
        $action = isset($_GET['action']) ? $_GET['action'] : 'caisse';
        $estOuvert = $this->modele->getEtatBar($idAsso);

        switch ($action) {
            case 'ouvrir':
                $this->modele->changerEtatBar($idAsso, 1);
                header("Location: index.php?module=barman&action=caisse");
                break;
            case 'fermer':
                $this->modele->changerEtatBar($idAsso, 0);
                $bilan = $this->modele->getBilanJournalier($idAsso);
                $this->vue->afficherBilanCloture($bilan);
                break;
            case 'caisse':
                if (!$estOuvert) {
                    $this->vue->afficherEcranFerme();
                } else {
                    $this->afficherCaisse();
                }
                break;
            case 'ajouter':
                if ($estOuvert) $this->ajouterAuPanier();
                else header("Location: index.php?module=barman&action=caisse");
                break;
            case 'vider':
                $this->viderPanier();
                break;
            case 'valider':
                if ($estOuvert) $this->validerVente();
                else header("Location: index.php?module=barman&action=caisse");
                break;
            case 'enregistrer_achat':
                $idFournisseur = isset($_POST['idFournisseur']) ? $_POST['idFournisseur'] : null;
                $montant = isset($_POST['montant']) ? $_POST['montant'] : 0;
                if ($idFournisseur && $montant > 0) {
                    $this->modele->enregistrerAchatFournisseur($idFournisseur, $montant);
                }
                header("Location: index.php?module=barman&action=caisse");
                break;
            default:
                header("Location: index.php?module=barman&action=caisse");
                break;
        }
        $this->vue->afficher();
    }

    private function afficherCaisse() {
        $idAsso = $_SESSION['idAsso'];
        $tresorerie = $this->modele->getTresorerieAssoc($idAsso);
        $this->vue->afficherTresorerie($tresorerie);
        $produits = $this->modele->getProduits($idAsso);
        $panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
        $clients = $this->modele->getListeClients($idAsso);
        $this->vue->afficherCaisse($produits, $panier, $clients);
    }

    private function ajouterAuPanier() {
        $idProduit = isset($_GET['id']) ? $_GET['id'] : null;
        if ($idProduit) {
            $produit = $this->modele->getProduitById($idProduit);
            if ($produit) {
                if (!isset($_SESSION['panier'])) {
                    $_SESSION['panier'] = [];
                }
                if (isset($_SESSION['panier'][$idProduit])) {
                    $_SESSION['panier'][$idProduit]['qte']++;
                } else {
                    $_SESSION['panier'][$idProduit] = [
                        'nom' => $produit['nom'],
                        'prix' => $produit['prix'],
                        'qte' => 1
                    ];
                }
            }
        }
        header("Location: index.php?module=barman&action=caisse");
    }

    private function viderPanier() {
        unset($_SESSION['panier']);
        header("Location: index.php?module=barman&action=caisse");
    }

    private function validerVente() {
        $idClient = isset($_POST['id_client']) ? $_POST['id_client'] : null;
        $total = isset($_POST['total']) ? $_POST['total'] : 0;
        $idAsso = $_SESSION['idAsso'];
        $success = false;
        if ($idClient && !empty($_SESSION['panier'])) {
            $success = $this->modele->enregistrerVente($idClient, $_SESSION['panier'], $total, $idAsso);
            if ($success) {
                unset($_SESSION['panier']);
            }
        }
        $this->vue->afficherConfirmation($success);
    }
}