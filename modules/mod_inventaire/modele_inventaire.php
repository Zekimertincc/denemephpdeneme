<?php
require_once 'Connexion.php';

class ModeleInventaire extends Connexion {
    public function getInventaireComplet($idAsso) {
        $query = self::getBdd()->prepare("SELECT * FROM produit WHERE idAsso = ? ORDER BY stock ASC");
        $query->execute([$idAsso]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatsAlerte($idAsso) {
        $query = self::getBdd()->prepare("SELECT COUNT(*) as nb FROM produit WHERE idAsso = ? AND stock <= seuilAlert");
        $query->execute([$idAsso]);
        return $query->fetch()['nb'];
    }
}