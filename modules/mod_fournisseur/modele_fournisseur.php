<?php
include_once "Connexion.php";

class ModeleFournisseur extends Connexion {

    public function getFournisseurs($idAsso) {
        $query = self::getBdd()->prepare("SELECT * FROM fournisseur WHERE idAsso = ? ORDER BY nom ASC");
        $query->execute([$idAsso]);
        return $query->fetchAll();
    }

    public function ajouterFournisseur($nom, $contact, $adresse, $idAsso) {
        $query = self::getBdd()->prepare("INSERT INTO fournisseur (nom, contact, adresse, idAsso) VALUES (?, ?, ?, ?)");
        return $query->execute([$nom, $contact, $adresse, $idAsso]);
    }

    public function getProduits($idAsso) {
        $query = self::getBdd()->prepare("SELECT idProduit, nom, stock FROM produit WHERE idAsso = ? ORDER BY nom ASC");
        $query->execute([$idAsso]);
        return $query->fetchAll();
    }

    public function mettreAJourStock($idProduit, $quantite, $idAsso, $idFournisseur = null, $prixAchat = 0) {
        $bdd = self::getBdd();
        try {
            $bdd->beginTransaction();

            $queryStock = $bdd->prepare("UPDATE produit SET stock = stock + ? WHERE idProduit = ? AND idAsso = ?");
            $queryStock->execute([$quantite, $idProduit, $idAsso]);

            if ($idFournisseur && $prixAchat > 0) {
                $queryArgent = $bdd->prepare("UPDATE fournisseur SET montant_total_achats = montant_total_achats + ? WHERE idFournisseur = ?");
                $queryArgent->execute([$prixAchat, $idFournisseur]);
            }

            $bdd->commit();
            return true;
        } catch (Exception $e) {
            $bdd->rollBack();
            return false;
        }
    }
}