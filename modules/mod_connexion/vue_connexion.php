<?php
include_once "VueGenerique.php";

class VueConnexion extends VueGenerique {
    function afficherFormulaireConnexion() {
        $this->affichage .= "<h1>Connexion</h1>
        <form action='index.php?module=connexion&action=connexion' method='POST'>
            <label for='login'>Identifiant :</label>
            <input type='text' id='login' name='login' required>
            <label for='password'>Mot de passe :</label>
            <input type='password' id='password' name='password' required>
            <button type='submit'>Confirmer</button>
        </form>";
    }

    function afficherFormulaireInscription() {
        $this->affichage .= "<h1>Inscription</h1>
        <form action='index.php?module=inscription&action=valider' method='POST'>
            <label for='nom'>Nom :</label><br>
            <input type='text' name='nom' id='nom' required><br>
            <label for='prenom'>Prénom :</label><br>
            <input type='text' name='prenom' id='prenom' required><br>
            <label for='email'>Email :</label><br>
            <input type='email' name='email' id='email' required><br>
            <label for='password'>Mot de passe :</label><br>
            <input type='password' id='password' name='password' required><br><br>
            <button type='submit'>Créer le compte</button>
        </form>";
    }

    public function afficherChoixAsso($associations) {
        $this->affichage .= "<h2>Choisissez votre Buvette / Association</h2>";
        $this->affichage .= "<div class='choix-asso'>";
        foreach ($associations as $asso) {
            $this->affichage .= "
                <a href='index.php?module=connexion&action=valider_choix&id=" . $asso['idAsso'] . "' style='display: block; padding: 10px; margin: 5px; background: #f0f0f0; text-decoration: none; color: black; border-radius: 5px;'>
                    " . htmlspecialchars($asso['nom']) . "
                </a>";
        }
        $this->affichage .= "</div>";
    }

    function liste() {
        if (isset($_SESSION['login'])) {
            $this->affichage .= '<p>Connecté sous : ' . htmlspecialchars($_SESSION["login"]) . '</p>';
            $this->affichage .= '<a href="index.php?module=connexion&action=deconnexion">Déconnexion</a>';
        } else {
            $this->affichage .= '<nav><ul>
                <li><a href="index.php?module=connexion&action=connexion">Se connecter</a></li>
                <li><a href="index.php?module=inscription">Inscription</a></li>
            </ul></nav>';
        }
    }

    function afficher() {
        echo $this->getAffichage();
    }
}