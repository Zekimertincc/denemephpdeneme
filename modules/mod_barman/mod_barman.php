<?php
include_once "cont_barman.php";

class ModBarman {
    private $controller;

    public function __construct() {
        $this->controller = new ContBarman();
        $this->controller->exec_action();
    }
}