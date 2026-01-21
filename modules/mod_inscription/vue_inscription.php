<?php

class VueInscription {

    private $affichage;

    public function __construct() {
        $this->affichage = "";
    }

    public function afficherFormulaire() {
        $this->affichage .= "
        <div style='max-width: 500px; margin: 20px auto; font-family: sans-serif;'>
            <h2>Créer un compte</h2>
            <form action='index.php?module=inscription&action=valider' method='post'>
                <label>Nom :</label><br>
                <input type='text' name='nom' required style='width: 100%; margin-bottom: 10px;'><br>
                
                <label>Prénom :</label><br>
                <input type='text' name='prenom' required style='width: 100%; margin-bottom: 10px;'><br>
                
                <label>Email :</label><br>
                <input type='email' name='email' required style='width: 100%; margin-bottom: 10px;'><br>
                
                <label>Mot de passe :</label><br>
                <input type='password' name='password' required style='width: 100%; margin-bottom: 10px;'><br>
                
                <input type='submit' value='Créer le compte' style='padding: 10px 20px; background: #27ae60; color: white; border: none; cursor: pointer;'>
            </form>
        </div>";
    }

    public function afficherResultat($success) {
        if ($success) {
            $this->affichage .= "<p style='color:green; text-align:center;'>Compte créé avec succès !</p>";
        } else {
            $this->affichage .= "<p style='color:red; text-align:center;'>Erreur lors de la création.</p>";
        }
        $this->affichage .= "<div style='text-align:center;'><a href='index.php'>Retour à l'accueil</a></div>";
    }

    public function afficher() {
        echo $this->affichage;
    }
}