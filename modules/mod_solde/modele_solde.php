<?php
include_once "Connexion.php";

class ModeleSolde extends Connexion {

    public function getListeClients() {
        $query = self::getBdd()->prepare("SELECT IDCLIENT, nom, prenom FROM utilisateur ORDER BY nom ASC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rechargerCompte($idClient, $montant, $methode) {
        $bdd = self::getBdd();
        $idAsso = $_SESSION['idAsso']; 

        try {
            $bdd->beginTransaction();

            $stmt1 = $bdd->prepare("INSERT INTO detient (IDCLIENT, idAsso, solde) VALUES (?, ?, ?) 
                                    ON DUPLICATE KEY UPDATE solde = solde + ?");
            $stmt1->execute([$idClient, $idAsso, $montant, $montant]);

            $stmt2 = $bdd->prepare("INSERT INTO recharge (montant, moyenPaiement, IDCLIENT, idAsso) VALUES (?, ?, ?, ?)");
            $stmt2->execute([$montant, $methode, $idClient, $idAsso]);

            $bdd->commit();
            return true;
        } catch (Exception $e) {
            $bdd->rollBack();
            return false;
        }
    }
}