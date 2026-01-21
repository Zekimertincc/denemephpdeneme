<?php
require_once 'Connexion.php';

class ModeleProduit extends Connexion {

    public function getListe($idAsso) {
        $query = self::getBdd()->prepare("SELECT * FROM produit WHERE idAsso = ?");
        $query->execute([$idAsso]);
        return $query->fetchAll();
    }

    public function getDetails($id) {
        $query = self::getBdd()->prepare("SELECT * FROM produit WHERE idProduit = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function ajoutProduit($data, $idAsso) {
        $query = self::getBdd()->prepare("INSERT INTO produit (nom, prix, stock, seuilAlert, categorie, idAsso) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([
            $data['nom'],
            $data['prix'],
            $data['stock'],
            $data['seuilAlert'],
            $data['categorie'],
            $idAsso
        ]);
    }
}