<?php
/**
 * Created by PhpStorm.
 * User: CANARIA
 * Date: 09/05/2018
 * Time: 17:35
 */

class Property
{
    private $_pdo = null;

    public function __construct(PDO $pdo)
    {
        $this->_pdo = $pdo;
    }

    public function login($pin){

        $stm = $this->_pdo->prepare('SELECT * FROM  properties WHERE properties.pin = :pin');
        $stm->bindParam(':pin', $pin, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);

    }

    public function register($data = []){
        $stm = $this->_pdo->prepare('UPDATE properties SET pin = :pin, email = :email WHERE apt = :apt');
        $stm->bindParam(':pin', $data['pin'], PDO::PARAM_INT);
        $stm->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stm->bindParam(':apt', $data['apt'], PDO::PARAM_INT);
        $status = $stm->execute();
        return $status;
    }

    public function getUnregistredApt(){
        $stm = $this->_pdo->prepare('SELECT apt FROM  properties WHERE isnull(properties.pin) ');
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_COLUMN);
    }



}