<?php
include_once "Connexion.php";

class ModeleHistorique extends Connexion {

    public function getHistoriqueComplet($idAsso) {
        $query = self::getBdd()->prepare("
            SELECT c.idCommande, c.dateCommande, c.total, u.nom AS nom_client, u.prenom AS prenom_client
            FROM commande c
            JOIN utilisateur u ON c.IDCLIENT = u.IDCLIENT
            WHERE c.idAsso = ?
            ORDER BY c.dateCommande DESC
        ");
        $query->execute([$idAsso]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailsCommande($idCommande) {
        $query = self::getBdd()->prepare("
            SELECT p.nom, cont.quantite, cont.prixUnitaire, (cont.quantite * cont.prixUnitaire) AS sous_total
            FROM contenir cont
            JOIN produit p ON cont.idProduit = p.idProduit
            WHERE cont.idCommande = ?
        ");
        $query->execute([$idCommande]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoriqueRecharges($idAsso) {
        $query = self::getBdd()->prepare("
            SELECT r.montant, r.moyenPaiement, u.nom AS nom_client, u.prenom AS prenom_client
            FROM recharge r
            JOIN utilisateur u ON r.IDCLIENT = u.IDCLIENT
            WHERE r.idAsso = ?
            ORDER BY r.idRecharge DESC
        ");
        $query->execute([$idAsso]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}