<?php
include_once "VueGenerique.php";

class VueProfil extends VueGenerique {
    public function afficherProfil($user) {
        $this->affichage .= "<h2>Mon Profil</h2>";
        $this->affichage .= "
        <form action='index.php?module=profil&action=sauvegarder' method='POST'>
            <label>Nom :</label><br>
            <input type='text' name='nom' value='" . htmlspecialchars($user['NOM'] ?? '') . "' required><br><br>
            <label>Prénom :</label><br>
            <input type='text' name='prenom' value='" . htmlspecialchars($user['PRENOM'] ?? '') . "' required><br><br>
            <label>Email :</label><br>
            <input type='email' name='email' value='" . htmlspecialchars($user['email'] ?? '') . "' required><br><br>
            <button type='submit'>Enregistrer les modifications</button>
        </form>";
    }

    public function afficherMessage($success) {
        $this->affichage .= $success ? "<p style='color:green'>Modifications enregistrées !</p>" : "<p style='color:red'>Erreur de sauvegarde.</p>";
        $this->affichage .= "<a href='index.php?module=profil'>Retour au profil</a>";
    }

    public function afficher() {
        echo $this->getAffichage();
    }
}