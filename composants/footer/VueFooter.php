<?php

class VueFooter
{
    private $footer;

    public function prepareFooter()
    {
        $year = date('Y');
        $this->footer = '<div class="text-center text-muted small py-4">MVC3 • E-BUVETTE • ' . $year . '</div>';
    }

    public function renderFooter()
    {
        echo $this->footer;
    }

}