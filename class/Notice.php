<?php

class Notice{
    
    private $_pdo = null;
    
    public function __construct(PDO $pdo){
        $this->_pdo = $pdo;
    }
    
    public function addNotice($data = []){
        $cycle = date('Ym', time());
        $title = $data['title'];
        $body = nl2br($data['body']);
        $stm = $this->_pdo->prepare('INSERT INTO notices(title, body, cycle) VALUES(:title, :body, :cycle)');
        $stm->bindParam(':title',$title, PDO::PARAM_STR);
        $stm->bindParam(':body', $body, PDO::PARAM_STR);
        $stm->bindParam(':cycle', $cycle, PDO::PARAM_INT);
        return $stm->execute();
    }
    
    public function show(){
        $stm = $this->_pdo->prepare('SELECT * FROM notices ORDER BY date_time DESC');
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getLastNotice(){
        $stm = $this->_pdo->prepare('SELECT * FROM notices ORDER BY `notices`.`cycle` DESC, date_time DESC LIMIT 1;');
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
}

