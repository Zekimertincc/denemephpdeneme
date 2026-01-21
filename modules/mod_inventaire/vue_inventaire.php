<?php
include_once "VueGenerique.php";

class VueInventaire extends VueGenerique {
    public function afficherInventaire($produits, $nbAlertes) {
        $this->affichage .= "<h2>Gestion de l'Inventaire</h2>";

        $this->affichage .= "<div style='display:flex; gap:20px; margin-bottom:20px;'>
                <div style='padding:20px; background:#e1f5fe; border-radius:10px; flex:1;'>
                    <strong>Total Produits:</strong> ".count($produits)."
                </div>
                <div style='padding:20px; background:".($nbAlertes > 0 ? '#ffebee' : '#e8f5e9')."; border-radius:10px; flex:1;'>
                    <strong>Alertes Stock:</strong> ".$nbAlertes."
                </div>
              </div>";

        $this->affichage .= "<table style='width:100%; border-collapse: collapse; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                <tr style='background-color: #2c3e50; color: white;'>
                    <th style='padding: 12px;'>Produit</th>
                    <th style='padding: 12px;'>Catégorie</th>
                    <th style='padding: 12px;'>Stock actuel</th>
                    <th style='padding: 12px;'>Seuil</th>
                    <th style='padding: 12px;'>Action conseillée</th>
                </tr>";

        foreach ($produits as $p) {
            $isCritical = ($p['stock'] <= $p['seuilAlert']);
            $bg = $isCritical ? "#fff5f5" : "#ffffff";
            
            $this->affichage .= "<tr style='background-color: $bg; border-bottom: 1px solid #eee;'>
                    <td style='padding: 12px;'>".htmlspecialchars($p['nom'])."</td>
                    <td style='padding: 12px;'>".htmlspecialchars($p['categorie'])."</td>
                    <td style='padding: 12px; font-weight:bold; color:".($isCritical ? 'red' : 'green')."'>".$p['stock']."</td>
                    <td style='padding: 12px;'>".$p['seuilAlert']."</td>
                    <td style='padding: 12px;'>".($isCritical ? "<strong>Commander!</strong>" : "RAS")."</td>
                  </tr>";
        }
        $this->affichage .= "</table>";
        $this->affichage .= "<br><a href='index.php?module=produit&action=ajout'><button style='padding:10px;'>+ Ajouter du stock (Produit)</button></a>";
    }

    public function afficher() {
        echo $this->getAffichage();
    }
}