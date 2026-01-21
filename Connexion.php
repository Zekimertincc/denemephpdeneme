<?php

class Connexion
{

    protected static $bdd;

    public static function initConnexion()
    {

        if (self::$bdd == null) {
            try {

                self::$bdd = new PDO('mysql:host=127.0.0.1;dbname=saebarman;', 'root', '');
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }

        }
    }

    protected static function getBdd()
    {
        return self::$bdd;
    }
}