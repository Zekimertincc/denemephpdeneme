<?php
include_once "cont_fournisseur.php";

class ModFournisseur {
    public function __construct() {
        $cont = new ContFournisseur();
        $cont->exec_action();
    }
}