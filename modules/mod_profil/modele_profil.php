<?php
include_once "Connexion.php";

class ModeleProfil extends Connexion {
    public function getInfosUser($login) {
        $query = self::getBdd()->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $query->execute([$login]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierInfos($ancienEmail, $nom, $prenom, $nouvelEmail) {
        $sql = "UPDATE utilisateur SET NOM = ?, PRENOM = ?, email = ? WHERE email = ?";
        $query = self::getBdd()->prepare($sql);
        $res = $query->execute([$nom, $prenom, $nouvelEmail, $ancienEmail]);
        
        if ($res && $ancienEmail !== $nouvelEmail) {
            $_SESSION['login'] = $nouvelEmail;
        }
        return $res;
    }
}