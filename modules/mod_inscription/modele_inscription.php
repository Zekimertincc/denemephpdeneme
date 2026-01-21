<?php
require_once 'Connexion.php';

class ModeleInscription extends Connexion {
    public function inscrireUtilisateur($data) {
        $bdd = self::getBdd();
        $idAsso = $_SESSION['idAsso'] ?? null;
        $role = $data['role'] ?? 'client';
        $roleAutorises = ['client', 'gestionnaire'];

        if (!in_array($role, $roleAutorises, true)) {
            $role = 'client';
        }

        $statut = $role === 'gestionnaire' ? 'EN_ATTENTE' : 'ACTIF';
        
        try {
            $bdd->beginTransaction(); 

            $hashPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $queryUser = $bdd->prepare("INSERT INTO utilisateur (NOM, PRENOM, email, MOTDEPASSE, statut) VALUES (?, ?, ?, ?, ?)");
            $queryUser->execute([
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $hashPassword,
                $statut
            ]);

            $idClient = $bdd->lastInsertId();

            $queryRole = $bdd->prepare("INSERT INTO role (Type, idApppro) VALUES (?, ?)");
            $queryRole->execute([$role, $idClient]);

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
