<?php
include_once "VueGenerique.php";

class VueProduit extends VueGenerique
{
    public function affiche_liste($produits)
    {
        $this->affichage .= "<h1>Liste des produits</h1>";
        $this->affichage .= "<ul>";

        foreach ($produits as $produit) {
            $this->affichage .= "<li>
                <a href='index.php?module=produit&action=details&id=" . $produit['idProduit'] . "'>" 
                . htmlspecialchars($produit['nom']) . "</a> 
                - " . htmlspecialchars($produit['prix']) . "€ (Stock: " . htmlspecialchars($produit['stock']) . ")
            </li>";
        }
        $this->affichage .= "</ul>";
        
        $this->affichage .= '
        <a href="index.php">
            <button>Retour à la liste des modules</button>
        </a>';
        
        $this->affichage .= '<a href="index.php?module=produit&action=ajout">
                     <button>Ajoute un nouveau produit</button>
                 </a>';
    }

    public function affiche_details($produit)
    {
        if ($produit) {
            $this->affichage .= "<h1>Détails du produit : " . htmlspecialchars($produit['nom']) . "</h1>";
            $this->affichage .= "<ul>";
            $this->affichage .= "<li><strong>Prix :</strong> " . htmlspecialchars($produit['prix']) . " €</li>";
            $this->affichage .= "<li><strong>Stock actuel :</strong> " . htmlspecialchars($produit['stock']) . "</li>";
            $this->affichage .= "<li><strong>Seuil d'alerte :</strong> " . htmlspecialchars($produit['seuilAlert']) . "</li>";
            $this->affichage .= "<li><strong>Catégorie :</strong> " . htmlspecialchars($produit['categorie']) . "</li>";
            $this->affichage .= "</ul>";
            
            $this->affichage .= '<a href="index.php?module=produit&action=liste">
                     <button>Retour à la liste des produits</button>
                 </a>';
        } else {
            $this->affichage .= "<p>Aucun produit trouvé.</p>";
        }
    }

    public function form_ajout()
    {
        $this->affichage .= "<h1>Ajouter un nouveau produit</h1>";
        $this->affichage .= "
        <form action='index.php?module=produit&action=ajout' method='post'>
            <label for='nom'>Nom du produit :</label><br>
            <input type='text' name='nom' id='nom' required><br><br>
            
            <label for='prix'>Prix (€) :</label><br>
            <input type='number' step='0.01' name='prix' id='prix' required><br><br>
            
            <label for='stock'>Stock initial :</label><br>
            <input type='number' name='stock' id='stock' value='0' required><br><br>
            
            <label for='seuilAlert'>Seuil d'alerte stock :</label><br>
            <input type='number' name='seuilAlert' id='seuilAlert' value='5' required><br><br>
            
            <label for='categorie'>Catégorie :</label><br>
            <input type='text' name='categorie' id='categorie' placeholder='Ex: Boisson, Snack'><br><br>
            
            <input type='hidden' name='csrf_token' value='" . ($_SESSION['csrf_token'] ?? '') . "'>
            <input type='submit' value='Enregistrer le produit'>
        </form>";

        $this->affichage .= '<br><a href="index.php?module=produit&action=liste">
                     <button>Annuler et retourner à la liste</button>
                 </a>';
    }

    public function affiche_confirmation()
    {
        $this->affichage .= "<div style='color: green; border: 1px solid green; padding: 10px; margin-bottom: 20px;'>
                Le produit a été ajouté avec succès dans la base de données !
              </div>";
        $this->affichage .= '<a href="index.php?module=produit&action=liste">
                     <button>Retour à la liste des produits</button>
                 </a>';
    }

    public function afficher()
    {
        echo $this->getAffichage();
    }
}