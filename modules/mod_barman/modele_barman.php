<?php
include_once "Connexion.php";

class ModeleBarman extends Connexion {

    public function getEtatBar($idAsso) {
        $query = self::getBdd()->prepare("SELECT bar_ouvert FROM association WHERE idAsso = ?");
        $query->execute([$idAsso]);
        return $query->fetchColumn();
    }

    public function changerEtatBar($idAsso, $etat) {
        $query = self::getBdd()->prepare("UPDATE association SET bar_ouvert = ? WHERE idAsso = ?");
        return $query->execute([$etat, $idAsso]);
    }

    public function getTresorerieAssoc($idAsso) {
        $bdd = self::getBdd();
        
        $queryVentes = $bdd->prepare("SELECT IFNULL(SUM(total), 0) FROM commande WHERE idAsso = ? AND type = 'Vente'");
        $queryVentes->execute([$idAsso]);
        $recettes = $queryVentes->fetchColumn();

        $queryAchats = $bdd->prepare("SELECT IFNULL(SUM(montant_total_achats), 0) FROM fournisseur WHERE idAsso = ?");
        $queryAchats->execute([$idAsso]);
        $depenses = $queryAchats->fetchColumn();

        return $recettes - $depenses;
    }

    public function enregistrerAchatFournisseur($idFournisseur, $montant) {
        $bdd = self::getBdd();
        $query = $bdd->prepare("UPDATE fournisseur SET montant_total_achats = montant_total_achats + ? WHERE idFournisseur = ?");
        return $query->execute([$montant, $idFournisseur]);
    }

    public function getBilanJournalier($idAsso) {
        $bdd = self::getBdd();
        $bilan = [];

        $queryVentes = $bdd->prepare("
            SELECT IFNULL(SUM(total), 0) as total_ventes, COUNT(*) as nb_commandes 
            FROM commande 
            WHERE idAsso = ? AND DATE(dateCommande) = CURDATE() AND type = 'Vente'
        ");
        $queryVentes->execute([$idAsso]);
        $bilan['infos'] = $queryVentes->fetch(PDO::FETCH_ASSOC);

        $queryTop = $bdd->prepare("
            SELECT p.nom, SUM(c.quantite) as total_qte 
            FROM contenir c 
            JOIN produit p ON c.idProduit = p.idProduit 
            JOIN commande cmd ON c.idCommande = cmd.idCommande 
            WHERE cmd.idAsso = ? AND DATE(cmd.dateCommande) = CURDATE()
            GROUP BY p.idProduit 
            ORDER BY total_qte DESC LIMIT 3
        ");
        $queryTop->execute([$idAsso]);
        $bilan['top_produits'] = $queryTop->fetchAll(PDO::FETCH_ASSOC);

        return $bilan;
    }

    public function getListeClients($idAsso) {
        $query = self::getBdd()->prepare("
            SELECT u.IDCLIENT, u.nom, u.prenom, IFNULL(d.solde, 0) as solde 
            FROM utilisateur u 
            LEFT JOIN detient d ON u.IDCLIENT = d.IDCLIENT AND d.idAsso = ? 
            ORDER BY u.nom ASC
        ");
        $query->execute([$idAsso]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduits($idAsso) {
        $query = self::getBdd()->prepare("SELECT * FROM produit WHERE idAsso = ? AND stock > 0");
        $query->execute([$idAsso]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitById($id) {
        $query = self::getBdd()->prepare("SELECT idProduit as id, nom, prix FROM produit WHERE idProduit = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function enregistrerVente($idClient, $panier, $total, $idAsso) {
        $bdd = self::getBdd();
        try {
            $bdd->beginTransaction();

            $stmtCheck = $bdd->prepare("SELECT solde FROM detient WHERE IDCLIENT = ? AND idAsso = ?");
            $stmtCheck->execute([$idClient, $idAsso]);
            $res = $stmtCheck->fetch();

            if (!$res || $res['solde'] < $total) {
                throw new Exception("Solde insuffisant");
            }

            $stmtCmd = $bdd->prepare("INSERT INTO commande (dateCommande, total, statut, type, IDCLIENT, idAsso) VALUES (NOW(), ?, 'Paye', 'Vente', ?, ?)");
            $stmtCmd->execute([$total, $idClient, $idAsso]);
            $idCommande = $bdd->lastInsertId();

            $stmtCont = $bdd->prepare("INSERT INTO contenir (idCommande, idProduit, quantite, prixUnitaire) VALUES (?, ?, ?, ?)");
            $stmtStock = $bdd->prepare("UPDATE produit SET stock = stock - ? WHERE idProduit = ? AND idAsso = ?");

            foreach ($panier as $idProd => $item) {
                $stmtCont->execute([$idCommande, $idProd, $item['qte'], $item['prix']]);
                $stmtStock->execute([$item['qte'], $idProd, $idAsso]);
            }

            $stmtUser = $bdd->prepare("UPDATE detient SET solde = solde - ? WHERE IDCLIENT = ? AND idAsso = ?");
            $stmtUser->execute([$total, $idClient, $idAsso]);

            $bdd->commit();
            return true;
        } catch (Exception $e) {
            $bdd->rollBack();
            return false;
        }
    }
}