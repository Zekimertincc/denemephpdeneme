<?php
include_once "VueFooter.php";

class ControllerFooter
{

    /**
     * @var VueFooter
     */
    private $vue;

    public function __construct()
    {
        $this->vue = new VueFooter();
        $this->vue->prepareFooter();
    }

    public function afficherFooter()
    {
        $this->vue->renderFooter();
    }

}