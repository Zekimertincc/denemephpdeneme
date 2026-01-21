<?php
include_once "VueGenerique.php";

class VueMenu extends VueGenerique {
    
    public function prepareMenu($estConnecte = false, $role = null) {
        // Menu bootstrap + styles du zip "merged-frontend"
        $this->affichage = '<nav class="d-flex gap-4 fw-semibold flex-wrap">';

        // Accueil
        $this->affichage .= '<a class="nav-link p-0" href="index.php">Accueil</a>';

        if ($estConnecte) {
            $this->affichage .= '<a class="nav-link p-0" href="index.php?module=barman">Barman (Vente)</a>';
            $this->affichage .= '<a class="nav-link p-0" href="index.php?module=historique">Historique</a>';

            if ($role === 'admin') {
                $this->affichage .= '<a class="nav-link p-0" href="index.php?module=inventaire">Inventaire</a>';
                $this->affichage .= '<a class="nav-link p-0" href="index.php?module=produit">Produits</a>';
                // Module présent côté Enes/Zeki
                $this->affichage .= '<a class="nav-link p-0" href="index.php?module=fournisseur">Fournisseurs</a>';
            }

            $this->affichage .= '<a class="nav-link p-0" href="index.php?module=solde">Solde</a>';
            $this->affichage .= '<a class="nav-link p-0" href="index.php?module=profil">Mon Profil</a>';
            $this->affichage .= '<a class="nav-link p-0" href="index.php?module=connexion&action=deconnexion">Déconnexion</a>';
        } else {
            $this->affichage .= '<a class="nav-link p-0" href="index.php?module=connexion">Connexion</a>';
            $this->affichage .= '<a class="nav-link p-0" href="index.php?module=inscription">Inscription</a>';
        }

        $this->affichage .= '</nav>';
    }

    public function afficher() {
        echo $this->getAffichage();
    }
}