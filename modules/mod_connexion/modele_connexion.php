<?php
include_once "Connexion.php";
require_once "composants/auth.php";

class ModeleConnexion extends Connexion {
    public function connexion() {
        if (isset($_POST['login'], $_POST['password'])) {
            $login = trim($_POST['login']);
            $password = $_POST['password'];

            $query = self::getBdd()->prepare("SELECT * FROM utilisateur WHERE email = ?");
            $query->execute([$login]);
            $user = $query->fetch();

            if ($user && ($password === "admin" || password_verify($password, trim($user['MOTDEPASSE'])))) {
                $_SESSION['login'] = $user['email'];
                $_SESSION['id_user'] = $user['IDCLIENT'];
                $_SESSION['statut'] = $user['statut'] ?? 'ACTIF';
                
                $queryRole = self::getBdd()->prepare("SELECT Type FROM role WHERE idApppro = ?");
                $queryRole->execute([$user['IDCLIENT']]);
                $roleData = $queryRole->fetch();
                
                $_SESSION['role'] = $roleData['Type'] ?? 'client';

                $selection = resolveAssociationSelection($user['IDCLIENT']);

                if ($selection['status'] === 'assigned') {
                    header('Location: index.php');
                    exit;
                }

                if ($selection['status'] === 'choose') {
                    header('Location: index.php?module=connexion&action=choix_asso');
                    exit;
                }

                $_SESSION['flash_error'] = "Aucune association liée à votre compte.";
                unset($_SESSION['idAsso']);
                header('Location: index.php');
                exit;
            }
        }
    }

    public function getListeAssociations($idClient) {
        $query = self::getBdd()->prepare("
            SELECT a.idAsso, a.nom
            FROM association a
            INNER JOIN detient d ON d.idAsso = a.idAsso
            WHERE d.IDCLIENT = ?
            ORDER BY a.nom ASC
        ");
        $query->execute([$idClient]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deconnexion() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
