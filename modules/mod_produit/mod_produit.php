<?php
include_once "cont_produit.php";

class ModProduit
{
    private $controller;

    public function __construct()
    {
        $this->controller = new ContProduit();
        $this->controller->exec_action();

    }
}

