<?php
include_once "VueGenerique.php";

class VueHistorique extends VueGenerique {
    public function afficherHistorique($commandes, $recharges) {
        $this->affichage .= "<h2>Historique de l'Association</h2>";

        $this->affichage .= "<h3>Ventes (Débits)</h3>";
        if (empty($commandes)) {
            $this->affichage .= "<p>Aucune vente enregistrée.</p>";
        } else {
            $this->affichage .= "<table border='1' style='width:100%; border-collapse: collapse; text-align: left; margin-bottom: 30px;'>
                    <tr style='background-color: #34495e; color: white;'>
                        <th style='padding:10px;'>Date</th>
                        <th style='padding:10px;'>Client</th>
                        <th style='padding:10px;'>Montant Total</th>
                    </tr>";

            foreach ($commandes as $c) {
                $this->affichage .= "<tr>
                        <td style='padding:10px;'>" . $c['dateCommande'] . "</td>
                        <td style='padding:10px;'>" . htmlspecialchars($c['prenom_client'] . " " . $c['nom_client']) . "</td>
                        <td style='padding:10px;'>" . $c['total'] . " €</td>
                      </tr>";
            }
            $this->affichage .= "</table>";
        }

        $this->affichage .= "<h3>Recharges (Crédits)</h3>";
        if (empty($recharges)) {
            $this->affichage .= "<p>Aucune recharge enregistrée.</p>";
        } else {
            $this->affichage .= "<table border='1' style='width:100%; border-collapse: collapse; text-align: left;'>
                    <tr style='background-color: #27ae60; color: white;'>
                        <th style='padding:10px;'>Client</th>
                        <th style='padding:10px;'>Montant</th>
                        <th style='padding:10px;'>Méthode</th>
                    </tr>";

            foreach ($recharges as $r) {
                $this->affichage .= "<tr>
                        <td style='padding:10px;'>" . htmlspecialchars($r['prenom_client'] . " " . $r['nom_client']) . "</td>
                        <td style='padding:10px;'>" . $r['montant'] . " €</td>
                        <td style='padding:10px;'>" . htmlspecialchars($r['moyenPaiement']) . "</td>
                      </tr>";
            }
            $this->affichage .= "</table>";
        }
        
        $this->affichage .= "<br><a href='index.php'><button>Retour Menu</button></a>";
    }

    public function afficher() {
        echo $this->getAffichage();
    }
}