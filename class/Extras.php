<?php

class Extras{
    
    private $_pdo = null;
    public static $AMOUNT = 1000;
    
    public function __construct(PDO $pdo){
        $this->_pdo = $pdo;
    }
    
    public function show(){
        $stm = $this->_pdo->prepare('CALL showExtras()');
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateExtras($cycle){
        $stm = $this->_pdo->prepare('CALL updateExtras(:cycle)');
        $stm->bindParam(':cycle', $cycle, PDO::PARAM_INT);
        $result = $stm->execute();
        return $result;
    }
    
    public function getLastExtras(){
        $stm = $this->_pdo->prepare('CALL getLastExtras()');
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public function getTotalExtras(){
        $stm = $this->_pdo->prepare('SELECT SUM(amount) FROM extras');
        $stm->execute();
        return $stm->fetch(PDO::FETCH_NUM);
    }
    
}

