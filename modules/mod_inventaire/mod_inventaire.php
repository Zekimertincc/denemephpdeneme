<?php
include_once 'cont_inventaire.php';

class ModInventaire {
    public function __construct() {
        $controleur = new ContInventaire();
        $controleur->exec_action();
    }
}