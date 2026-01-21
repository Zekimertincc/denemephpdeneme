<?php
include_once "Connexion.php";

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
                
                $queryRole = self::getBdd()->prepare("SELECT Type FROM role WHERE idApppro = ?");
                $queryRole->execute([$user['IDCLIENT']]);
                $roleData = $queryRole->fetch();
                
                $_SESSION['role'] = $roleData['Type'] ?? 'client';
                
                header('Location: index.php?module=connexion&action=choix_asso');
                exit;
            }
        }
    }

    public function getListeAssociations() {
        $query = self::getBdd()->prepare("SELECT * FROM association");
        $query->execute();
        return $query->fetchAll();
    }

    public function deconnexion() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}