<?php
include_once 'cont_historique.php';

class ModHistorique {
    public function __construct() {
        $controleur = new ContHistorique();
        $controleur->exec_action();
    }
}