<?php
/**
 * Created by PhpStorm.
 * User: CANARIA
 * Date: 09/05/2018
 * Time: 17:35
 */

class Situation
{
    private $_pdo = null;
    private static $donation = 100;

    public function __construct(PDO $pdo){
        $this->_pdo = $pdo;
    }

    public function changeStatus($apt, $cycle, $date){
        $stm = $this->_pdo->prepare('CALL changeStatus(:apt, :cycle, :date)');
        $stm->bindParam(':apt', $apt, PDO::PARAM_INT);
        $stm->bindParam(':cycle', $cycle, PDO::PARAM_INT);
        $stm->bindParam(':date', $date, PDO::PARAM_STR);
        return $stm->execute();
    }

    public function show(){
        $sql = '
                SELECT properties.`name`, situations.apt, situations.`status`, cycles.`cycle`
                FROM properties
                RIGHT JOIN situations
                ON properties.`apt` = situations.`apt`
                LEFT JOIN cycles
                ON situations.`cycle` = cycles.`id`
                order by cycle desc, apt;
            ';
        $stm = $this->_pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function showLimit($limit){
            $sql = '
                SELECT properties.`name`, situations.apt, situations.`status`, cycles.`cycle`
                FROM properties
                RIGHT JOIN situations
                ON properties.`apt` = situations.`apt`
                LEFT JOIN cycles
                ON situations.`cycle` = cycles.`id`
                order by cycle desc, apt
                LIMIT :limit;
            ';
        $stm = $this->_pdo->prepare($sql);
        $stm->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalDonation($cycle){
        $cycle = (int) $cycle;
        $stm = $this->_pdo->prepare('CALL getTotalDoantions(:cycle)');
        $stm->bindParam(':cycle', $cycle, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_COLUMN) * static::$donation;
        return $result;
    }
    
    public function createCycle(){
        date_default_timezone_set('Africa/Casablanca');
        $actual = (int) date('Ym');
        $stm = $this->_pdo->prepare('CALL createCycle(:cycle)');
        $stm->bindParam(':cycle', $actual, PDO::PARAM_INT);
        $stm->execute();
    }

    public function getLastCycle(){
        $stm = $this->_pdo->prepare('SELECT cycle AS c FROM cycles ORDER BY cycle DESC LIMIT 1');
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getLastUpdate(){
        $stm = $this->_pdo->prepare('SELECT date_time AS upd FROM situations ORDER BY date_time DESC LIMIT 1');
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getBalance(){
        $sql = 'call getBalance()';
        $stm = $this->_pdo->prepare($sql);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_COLUMN);
        return $result;
    }

    public function getProgress($cycle){
        $stm = $this->_pdo->prepare('CALL getProgress(:cycle);');
        $stm->bindParam(':cycle', $cycle, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getLastProgress(){
        $sql = 'CALL getLastCycleProgress();';
        $stm = $this->_pdo->prepare($sql);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getPersonalStats($apt){
        $apt = (int) $apt;
        $sql1 = 'CALL getPersonalStats(:apt, @ts, @tc, @c);';
        $stm = $this->_pdo->prepare($sql1);
        $stm->bindParam(':apt', $apt, PDO::PARAM_INT);
        $stm->execute();
        $sql2 = 'SELECT @ts as total_sum, @tc as total_cycles, @c as last_cycle;';
        $stm2 = $this->_pdo->prepare($sql2);
        $stm2->execute();
        $result = $stm2->fetch(PDO::FETCH_ASSOC);
        return $result;

    }




























}