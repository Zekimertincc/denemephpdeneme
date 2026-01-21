<?php
include_once "VueGenerique.php";

class VueSolde extends VueGenerique {
    public function afficherFormulaireRecharge($clients) {
        $this->affichage .= "<h1>Recharger un compte</h1>
        <form action='index.php?module=solde&action=valider' method='POST'>
            <label>Chercher le client (Nom Prénom) : </label><br>
            <input list='liste_clients' id='client_input' autocomplete='off' placeholder='Tapez le nom...' required style='width: 300px; padding: 5px;'>
            
            <datalist id='liste_clients'>";
            
        foreach ($clients as $c) {
            $nomComplet = htmlspecialchars($c['nom'] . " " . $c['prenom']);
            $this->affichage .= "<option data-id='" . $c['IDCLIENT'] . "' value='" . $nomComplet . "'>";
        }

        $this->affichage .= "
            </datalist>
            
            <input type='hidden' name='idClient' id='idClient_hidden'>

            <br><br>
            <label>Montant (€) : </label><br>
            <input type='number' name='montant' step='0.01' required><br><br>
            
            <label>Moyen de paiement : </label><br>
            <select name='methode'>
                <option value='Espèces'>Espèces</option>
                <option value='CB'>Carte Bancaire</option>
            </select><br><br>
            
            <button type='submit'>Valider la recharge</button>
        </form>

        <script>
        document.getElementById('client_input').addEventListener('input', function(e) {
            var input = e.target;
            var list = input.getAttribute('list');
            var options = document.querySelectorAll('#' + list + ' option');
            var hiddenInput = document.getElementById('idClient_hidden');
            
            hiddenInput.value = '';
            for(var i = 0; i < options.length; i++) {
                if(options[i].value === input.value) {
                    hiddenInput.value = options[i].getAttribute('data-id');
                    break;
                }
            }
        });
        </script>";
    }

    public function afficherConfirmation($success) {
        if ($success) {
            $this->affichage .= "<p style='color:green;'>Compte rechargé avec succès !</p>";
        } else {
            $this->affichage .= "<p style='color:red;'>Erreur lors du rechargement (Client introuvable ou erreur serveur).</p>";
        }
        $this->affichage .= "<a href='index.php'>Retour accueil</a>";
    }

    public function afficher() {
        echo $this->getAffichage();
    }
}