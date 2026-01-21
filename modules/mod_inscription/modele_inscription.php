<?php
require_once 'Connexion.php';

class ModeleInscription extends Connexion {
    public function inscrireUtilisateur($data) {
        $bdd = self::getBdd();
        $idAsso = $_SESSION['idAsso'] ?? null;
        
        try {
            $bdd->beginTransaction(); 

            $hashPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $queryUser = $bdd->prepare("INSERT INTO utilisateur (NOM, PRENOM, email, MOTDEPASSE) VALUES (?, ?, ?, ?)");
            $queryUser->execute([
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $hashPassword
            ]);

            $idClient = $bdd->lastInsertId();

            $queryRole = $bdd->prepare("INSERT INTO role (Type, idApppro) VALUES ('client', ?)");
            $queryRole->execute([$idClient]);

            if ($idAsso) {
                $queryDetient = $bdd->prepare("INSERT INTO detient (IDCLIENT, idAsso, solde) VALUES (?, ?, 0)");
                $queryDetient->execute([$idClient, $idAsso]);
            }

            $bdd->commit();
            return true;
        } catch (Exception $e) {
            $bdd->rollBack();
            return false;
        }
    }
}