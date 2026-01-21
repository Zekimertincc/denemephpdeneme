<?php
include_once "cont_solde.php";

class ModSolde {
    private $controller;

    public function __construct() {
        $this->controller = new ContSolde();
        $this->controller->exec_action();
    }
}