<?php

require_once('config.php');

class Database
{

    private static $_pdo = null;

    public static function getInstance()
    {

        try {

            self::$_pdo = new PDO('mysql:host=' . HOST . ';dbname=' . DATABASE, USER, PASSWORD);

            return self::$_pdo;

        } catch (PDOException $e) {

            return $e->getMessage();

        }

    }

}