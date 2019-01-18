<?php
/**
 * Created by PhpStorm.
 * User: CANARIA
 * Date: 09/05/2018
 * Time: 21:31
 */

class Admin
{

    private $_pdo = null;

    public function __construct(PDO $pdo)
    {
        $this->_pdo = $pdo;
    }

    public function login($data = []){

        $stm = $this->_pdo->prepare('SELECT * FROM  admin WHERE username = :username and pass = :pass');
        $stm->bindParam(':username', $data['username'], PDO::PARAM_STR);
        $stm->bindParam(':pass', $data['pass'], PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);

    }

    public function changeStatus($data = []){
        $stm = $this->_pdo->prepare('UPDATE situations SET status = 1 WHERE apt = :apt');
        $stm->bindParam(':apt', $data['apt'], PDO::PARAM_INT);
        $status = $stm->execute();
        return $status;
    }
    
    public function register($data = []){
        $stm = $this->_pdo->prepare('insert into admin(username, pass, names) values(:username, :pass, :names)');
        $stm->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $stm->bindParam(':pass', $_POST['pass'], PDO::PARAM_STR);
        $stm->bindParam(':names', $_POST['names'], PDO::PARAM_STR);
        $result = $stm->execute();
        return $result;
    }
    
    public function checkAdmin(){
        $stm = $this->_pdo->prepare('select * from admin');
        $stm->execute();
        return $stm->fetch(PDO::FETCH_NUM);
    }
}