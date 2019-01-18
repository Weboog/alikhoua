<?php

class Spends {


	private $_pdo = null;

	public function __construct(PDO $pdo){
		$this->_pdo = $pdo;
	}

	public function addSpend($data = [], $timestamp){
		$date = date('Ym');
		$stm = $this->_pdo->prepare('INSERT INTO spends(type, amount, cycle, date_time) VALUES(:type, :amount, :date, :date_time)');
		$stm->bindParam(':type', $data['type'], PDO::PARAM_INT);
		$stm->bindParam(':amount', $data['amount'], PDO::PARAM_INT);
		$stm->bindParam(':date', $date, PDO::PARAM_STR);
        $stm->bindParam(':date_time', $timestamp, PDO::PARAM_STR);
		return $stm->execute();
	}

	public function show(){
		$stm = $this->_pdo->prepare('SELECT * FROM spends ORDER BY cycle DESC , date_time DESC');
		$stm->execute();
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function getLastCycle(){
        $stm = $this->_pdo->prepare('SELECT cycle AS c FROM spends ORDER BY cycle DESC LIMIT 1');
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

	public function getLastUpdate(){
        $stm = $this->_pdo->prepare('SELECT date_time AS upd FROM spends ORDER BY date_time DESC LIMIT 1');
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

	public function getLastSpends(){
		$stm = $this->_pdo->prepare('CALL getLastSpends()');
		$stm->execute();
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function getTotalSpends($cycle){
		$stm = $this->_pdo->prepare('SELECT SUM(amount) AS total_spends FROM spends WHERE cycle = :cycle');
		$stm->bindParam(':cycle', $cycle, PDO::PARAM_STR);
		$stm->execute();
		return $result = $stm->fetch(PDO::FETCH_COLUMN);
	}

	public function getTypes(){
		$stm = $this->_pdo->prepare('SELECT * FROM spend_type');
		$stm->execute();
		return $result = $stm->fetchAll(PDO::FETCH_NUM);
	}

}