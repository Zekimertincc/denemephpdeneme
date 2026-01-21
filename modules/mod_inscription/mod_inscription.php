<?php
require_once 'cont_inscription.php';

class ModInscription {
    public function __construct() {
        $controller = new ContInscription();
        $controller->exec_action();
    }
}