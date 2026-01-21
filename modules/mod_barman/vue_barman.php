<?php
include_once "VueGenerique.php";

class VueBarman extends VueGenerique {

    public function afficherTresorerie($montant) {
        $couleur = ($montant >= 0) ? "#27ae60" : "#e74c3c";
        
        $this->affichage .= "
        <div style='background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #eee; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; margin-bottom: 30px; font-family: sans-serif;'>
            <h3 style='color: #7f8c8d; margin: 0; text-transform: uppercase; font-size: 0.85em; letter-spacing: 1px;'>Tresorerie Actuelle</h3>
            <div style='font-size: 3.5em; font-weight: 900; color: $couleur; margin: 10px 0;'>
                " . number_format($montant, 2, ',', ' ') . " EUR
            </div>
            <div style='display: inline-block; padding: 5px 15px; background: #f8f9fa; border-radius: 20px; font-size: 0.8em; color: #95a5a6;'>
                Etat financier global en temps reel
            </div>
        </div>";
    }

    public function afficherBilanMensuel($bilan, $dateDebut, $dateFin) {
        $this->affichage .= "
        <div style='max-width: 800px; margin: 20px auto; font-family: sans-serif;'>
            <h2 style='color: #2c3e50;'>Bilan Analytique Mensuel</h2>
            
            <form action='index.php?module=barman&action=bilan_mensuel' method='POST' style='background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 15px; align-items: flex-end;'>
                <div>
                    <label style='font-size: 0.8em; color: #7f8c8d;'>DU</label><br>
                    <input type='date' name='date_debut' value='$dateDebut' style='padding: 8px; border: 1px solid #ccc;'>
                </div>
                <div>
                    <label style='font-size: 0.8em; color: #7f8c8d;'>AU</label><br>
                    <input type='date' name='date_fin' value='$dateFin' style='padding: 8px; border: 1px solid #ccc;'>
                </div>
                <button type='submit' style='padding: 8px 20px; background: #34495e; color: white; border: none; cursor: pointer;'>ANALYSER</button>
            </form>

            <div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 30px;'>
                <div style='background: #fff; border: 1px solid #eee; padding: 15px; text-align: center;'>
                    <span style='color: #7f8c8d; font-size: 0.8em;'>VENTES CLIENTS</span><br>
                    <strong style='font-size: 1.2em; color: #27ae60;'>" . number_format($bilan['ventes'], 2) . " €</strong>
                </div>
                <div style='background: #fff; border: 1px solid #eee; padding: 15px; text-align: center;'>
                    <span style='color: #7f8c8d; font-size: 0.8em;'>ACHATS FOURNISSEURS</span><br>
                    <strong style='font-size: 1.2em; color: #e74c3c;'>" . number_format($bilan['achats'], 2) . " €</strong>
                </div>
                <div style='background: #fff; border: 1px solid #eee; padding: 15px; text-align: center; border-top: 3px solid #2980b9;'>
                    <span style='color: #7f8c8d; font-size: 0.8em;'>MARGE ESTIMEE</span><br>
                    <strong style='font-size: 1.2em; color: #2980b9;'>" . number_format($bilan['ventes'] - $bilan['achats'], 2) . " €</strong>
                </div>
            </div>
            <a href='index.php?module=barman&action=caisse' style='text-decoration: none; color: #3498db;'>← Retour au tableau de bord</a>
        </div>";
    }

    public function afficherBilanCloture($bilan) {
        $total = $bilan['infos']['total_ventes'] ?? 0;
        $nbCmd = $bilan['infos']['nb_commandes'] ?? 0;

        $this->affichage .= "
        <div style='max-width: 600px; margin: 30px auto; padding: 30px; border: 1px solid #ccc; border-radius: 10px; background-color: #fff; font-family: sans-serif;'>
            <h2 style='text-align:center; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px;'>Bilan de cloture de la journee</h2>
            <div style='display: flex; justify-content: space-around; margin: 30px 0;'>
                <div style='text-align:center;'>
                    <span style='font-size: 0.9em; color: #7f8c8d; text-transform: uppercase;'>Total Ventes</span><br>
                    <strong style='font-size: 1.8em; color: #27ae60;'>" . number_format($total, 2) . " EUR</strong>
                </div>
                <div style='text-align:center;'>
                    <span style='font-size: 0.9em; color: #7f8c8d; text-transform: uppercase;'>Commandes</span><br>
                    <strong style='font-size: 1.8em; color: #2980b9;'>" . $nbCmd . "</strong>
                </div>
            </div>
            <h3 style='color: #34495e; font-size: 1.1em; border-left: 4px solid #2980b9; padding-left: 10px;'>Top 3 des produits vendus</h3>
            <table style='width:100%; border-collapse: collapse; margin-top: 15px;'>";
        
        if (empty($bilan['top_produits'])) {
            $this->affichage .= "<tr><td colspan='2' style='padding:10px; color:#7f8c8d;'>Aucune vente enregistree aujourd'hui.</td></tr>";
        } else {
            foreach ($bilan['top_produits'] as $p) {
                $this->affichage .= "
                <tr style='border-bottom: 1px solid #eee;'>
                    <td style='padding: 12px;'>" . htmlspecialchars($p['nom']) . "</td>
                    <td style='padding: 12px; text-align: right;'><strong>" . $p['total_qte'] . "</strong> unites</td>
                </tr>";
            }
        }
        $this->affichage .= "
            </table>
            <div style='text-align:center; margin-top: 30px; padding: 20px; background-color: #fcf8f2; border-radius: 5px;'>
                <p style='color: #e67e22; font-weight: bold; margin: 0;'>Le service est desormais verrouille.</p>
                <br>
                <a href='index.php?module=barman&action=caisse'>
                    <button style='padding: 10px 25px; background-color: #34495e; color: white; border: none; border-radius: 5px; cursor:pointer;'>Retour a l'accueil</button>
                </a>
            </div>
        </div>";
    }

    public function afficherEcranFerme() {
        $this->affichage .= "
        <div style='text-align:center; margin-top:50px; padding:40px; border:2px dashed #e74c3c; border-radius:15px; background-color:#fff5f5;'>
            <h2 style='color:#e74c3c;'>Le Bar est actuellement FERME</h2>
            <p style='font-size:1.1em;'>Aucune vente ne peut etre realisee pour le moment.</p>
            <br>
            <a href='index.php?module=barman&action=ouvrir'>
                <button style='padding:15px 30px; background-color:#27ae60; color:white; border:none; border-radius:8px; font-size:1.2em; cursor:pointer; font-weight:bold;'>
                    OUVRIR LE SERVICE
                </button>
            </a>
        </div>";
    }

    public function afficherCaisse($produits, $panier, $clients) {
        $this->affichage .= "
        <div style='display:flex; justify-content:space-between; align-items:center;'>
            <h2>Interface de Vente (Caisse)</h2>
            <a href='index.php?module=barman&action=fermer'>
                <button style='padding:8px 15px; background-color:#c0392b; color:white; border:none; border-radius:5px; cursor:pointer;'>
                    FERMER LE BAR
                </button>
            </a>
        </div>";

        $this->affichage .= "<div style='display:flex; flex-wrap:wrap; gap:20px; margin-top:20px;'>";
        $this->affichage .= "<div style='flex:2; min-width:300px;'><h3>Produits disponibles</h3>";
        $this->affichage .= "<div style='display:grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap:10px;'>";
        
        foreach ($produits as $p) {
            $stock = $p['stock'] ?? 0;
            $styleStock = ($stock <= 5) ? "border:2px solid red; background-color:#ffeded;" : "background-color:#f0f0f0;";
            $labelAlerte = ($stock <= 5) ? "<br><span style='color:red;font-size:0.8em;'>Reste: $stock</span>" : "";

            $this->affichage .= "
            <a href='index.php?module=barman&action=ajouter&id=" . $p['idProduit'] . "' style='text-decoration:none;'>
                <button style='width:100%; height:90px; cursor:pointer; $styleStock border:1px solid #ccc; border-radius:8px;'>
                    <strong>" . htmlspecialchars($p['nom']) . "</strong><br>" . $p['prix'] . " EUR
                    $labelAlerte
                </button>
            </a>";
        }
        $this->affichage .= "</div></div>";

        $this->affichage .= "<div style='flex:1; min-width:250px; border-left:1px solid #ccc; padding-left:20px; background-color:#fafafa;'>";
        $this->affichage .= "<h3>Panier actuel</h3>";
        $total = 0;
        
        if (empty($panier)) {
            $this->affichage .= "<p>Le panier est vide.</p>";
        } else {
            $this->affichage .= "<ul style='list-style:none; padding:0;'>";
            foreach ($panier as $item) {
                $sousTotal = $item['prix'] * $item['qte'];
                $total += $sousTotal;
                $this->affichage .= "<li style='margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:5px;'>
                    <strong>" . htmlspecialchars($item['nom']) . "</strong><br>
                    Quantite : " . $item['qte'] . " | Total : " . $sousTotal . " EUR
                </li>";
            }
            $this->affichage .= "</ul>";
            $this->affichage .= "<h4 style='font-size:1.2em; color:#2c3e50;'>TOTAL A PAYER : " . $total . " EUR</h4>";
            
            $this->affichage .= "
            <form action='index.php?module=barman&action=valider' method='POST' style='margin-top:20px;'>
                <input type='hidden' name='total' value='" . $total . "'>
                <label><strong>Chercher le Client :</strong></label><br>
                <div id='alerte_solde_faible' style='color:red; font-weight:bold; display:none;'>Alerte: Solde Faible!</div>
                <input list='liste_clients' id='client_input' autocomplete='off' placeholder='Tapez le nom...' required style='width:100%; padding:8px; margin-top:5px;'>
                <datalist id='liste_clients'>";
                
            foreach ($clients as $c) {
                $nomComplet = htmlspecialchars($c['nom'] . " " . $c['prenom']);
                $soldeClient = $c['solde'] ?? 0;
                $this->affichage .= "<option data-id='" . $c['IDCLIENT'] . "' data-solde='" . $soldeClient . "' value='" . $nomComplet . "'>";
            }

            $this->affichage .= "
                </datalist>
                <input type='hidden' name='id_client' id='id_client_hidden'>
                <br><br>
                <button type='submit' style='width:100%; padding:15px; background-color:#27ae60; color:white; border:none; border-radius:5px; font-weight:bold; cursor:pointer;'>
                    VALIDER LE DEBIT
                </button>
            </form>
            <script>
            document.getElementById('client_input').addEventListener('input', function(e) {
                var input = e.target;
                var list = input.getAttribute('list');
                var options = document.querySelectorAll('#' + list + ' option');
                var hiddenInput = document.getElementById('id_client_hidden');
                var alerte = document.getElementById('alerte_solde_faible');
                hiddenInput.value = '';
                alerte.style.display = 'none';
                for(var i = 0; i < options.length; i++) {
                    if(options[i].value === input.value) {
                        hiddenInput.value = options[i].getAttribute('data-id');
                        var solde = parseFloat(options[i].getAttribute('data-solde'));
                        if (solde < 5) alerte.style.display = 'block';
                        break;
                    }
                }
            });
            </script>";
            $this->affichage .= "<br><a href='index.php?module=barman&action=vider' style='color:#e74c3c;'>Vider le panier</a>";
        }
        $this->affichage .= "</div></div>";
    }

    public function afficherConfirmation($success) {
        if ($success) {
            $this->affichage .= "<div style='padding:20px; background-color:#d4edda; color:#155724; border-radius:5px; margin-top:20px;'>
                <strong>Succes !</strong> La vente a ete enregistree.
            </div>";
        } else {
            $this->affichage .= "<div style='padding:20px; background-color:#f8d7da; color:#721c24; border-radius:5px; margin-top:20px;'>
                <strong>Erreur !</strong> Impossible de valider la commande.
            </div>";
        }
        $this->affichage .= "<br><a href='index.php?module=barman&action=caisse'><button style='padding:10px 20px; cursor:pointer;'>Retour a l'accueil</button></a>";
    }

    public function afficher() {
        echo $this->getAffichage();
    }
}