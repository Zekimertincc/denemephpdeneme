<?php
include_once "VueGenerique.php";

class VueFournisseur extends VueGenerique {

    public function afficherListe($fournisseurs) {
        $this->affichage .= "<h2>Gestion des Fournisseurs</h2>";
        $this->affichage .= "<div style='margin-bottom: 20px;'>
            <a href='index.php?module=fournisseur&action=formulaire_ajout'><button style='padding:10px;'>+ Nouveau Fournisseur</button></a>
            <a href='index.php?module=fournisseur&action=passer_commande'><button style='padding:10px; background-color: #2980b9; color: white;'>Commander du Stock</button></a>
        </div>";
        
        $this->affichage .= "<table border='1' style='width:100%; border-collapse: collapse;'>
            <tr style='background-color: #f2f2f2;'>
                <th style='padding: 10px;'>Nom</th>
                <th>Contact</th>
                <th>Adresse</th>
            </tr>";
        foreach ($fournisseurs as $f) {
            $this->affichage .= "<tr>
                <td style='padding: 10px;'>" . htmlspecialchars($f['nom']) . "</td>
                <td>" . htmlspecialchars($f['contact']) . "</td>
                <td>" . htmlspecialchars($f['adresse']) . "</td>
            </tr>";
        }
        $this->affichage .= "</table>";
    }

    public function formulaireAjout() {
        $this->affichage .= "<h2>Ajouter un Fournisseur</h2>
        <form action='index.php?module=fournisseur&action=valider_ajout' method='POST'>
            <label>Nom :</label><br><input type='text' name='nom' required style='width:300px; padding:5px;'><br><br>
            <label>Contact :</label><br><input type='text' name='contact' style='width:300px; padding:5px;'><br><br>
            <label>Adresse :</label><br><input type='text' name='adresse' style='width:300px; padding:5px;'><br><br>
            <button type='submit' style='padding:10px 20px;'>Enregistrer</button>
        </form>";
    }

    public function formulaireCommande($fournisseurs, $produits) {
        $this->affichage .= "<h2>Réception de marchandises</h2>
        <form action='index.php?module=fournisseur&action=valider_commande' method='POST'>
            <label>Fournisseur :</label><br>
            <select name='idFournisseur' required style='width:310px; padding:5px;'>";
            foreach ($fournisseurs as $f) {
                $this->affichage .= "<option value='".$f['idFournisseur']."'>".htmlspecialchars($f['nom'])."</option>";
            }
        $this->affichage .= "</select><br><br>

            <label>Produit reçu :</label><br>
            <select name='idProduit' required style='width:310px; padding:5px;'>";
            foreach ($produits as $p) {
                $this->affichage .= "<option value='".$p['idProduit']."'>".htmlspecialchars($p['nom'])." (Actuel: ".$p['stock'].")</option>";
            }
        $this->affichage .= "</select><br><br>

            <label>Quantité ajoutée au stock :</label><br>
            <input type='number' name='quantite' min='1' required style='width:300px; padding:5px;'><br><br>

            <label><strong>Prix total de l'achat (EUR) :</strong></label><br>
            <input type='number' name='prixTotal' step='0.01' min='0' placeholder='Ex: 45.50' required style='width:300px; padding:5px; border: 2px solid #27ae60;'><br><br>

            <button type='submit' style='padding:10px 20px; background-color: #27ae60; color:white; border:none; cursor:pointer; font-weight:bold;'>Valider l'entrée en stock et le paiement</button>
        </form>";
    }

    public function afficher() {
        echo $this->getAffichage();
    }
}