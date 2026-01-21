<?php
include_once "VueMenu.php";

class ControllerMenu
{
    private $vue;

    public function __construct()
    {
        $this->vue = new VueMenu();
    }

    public function afficherMenu()
    {
        $estConnecte = isset($_SESSION['login']);
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

        $this->vue->prepareMenu($estConnecte, $role);
        $this->vue->afficher();
    }
}