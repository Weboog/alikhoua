<?php
session_start();
if(!isset($_SESSION['pin'])){
    header('location: ./auth/login.php');
    exit();
}
spl_autoload_register('load');
function load($class){
    require './class/'.$class.'.php';
}
$pdo = Database::getInstance();
$s = new Situation($pdo);
$sp = new Spends($pdo);
$ntc = new Notice($pdo);
$months = array('Janvier', 'Fervrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');
$types = array(
    'menage'=>'Femme de ménage',
    'electricity'=>'Facture d\'électricite',
    'ascenseur'=>'Maintenance d\'ascenseur',
    'reparations'=>'Réparations',
    'ampoules'=>'Changement d\'ampoules
        ');
//Get last cycle
//date_default_timezone_set('Africa/Casablanca');
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/solid.css" integrity="sha384-Rw5qeepMFvJVEZdSo1nDQD5B6wX0m7c5Z/pLNvjkB14W6Yki1hKbSEQaX9ffUbWe" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/regular.css" integrity="sha384-EWu6DiBz01XlR6XGsVuabDMbDN6RT8cwNoY+3tIH+6pUCfaNldJYJQfQlbEIWLyA" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/fontawesome.css" integrity="sha384-GVa9GOgVQgOk+TNYXu7S/InPTfSDTtBalSgkgqQ7sCik56N9ztlkoTr2f/T44oKV" crossorigin="anonymous">
    <script type="text/javascript" src='./js/alt.js'></script>
    <title>Tableau de bord</title>
    </head>
    <body>
    <?php require_once('./include/header.php'); ?>   
    	<section id="notice">	
    		
    			<?php
            $ntc = new Notice($pdo);
            $last_ntc = $ntc->getLastNotice();
            if($last_ntc){
                $h2 = '<div><h2><span>Notice</span> : %s</h2>';
                $purified_title = preg_replace("#^'|'$#", "", stripslashes($last_ntc['title']));
                echo sprintf($h2, htmlspecialchars_decode($purified_title));
                $p = '<p>%s</p></div>';
                $purified_ex = stripslashes($last_ntc['body']);
                echo sprintf($p, $purified_ex);
            }
            //Extract Data from situations
            $result = $s->showLimit(168);
            ?>
    		
		</section>
	<main>
		<p class="select">
		<label for="cycles">Choisir un mois: </label>
    		<select name="cycles" id="cycles">
    			<?php 
    			$cycles = array();
    			for($i = 1; $i <= count($result)/14; $i++){
    			    if(!in_array($result[$i*13]['cycle'], $cycles)){
    			        $cycles[] = $result[$i*13]['cycle'];
    			    }
    			}
    			foreach ($cycles as $c){
    			    $cf = date_create_from_format('Ym', $c);
    			    $cf = date_format($cf, 'm/Y');
    			    echo '<option value="'.$c.'">'.$cf.'</option>';
    			}
		          ?>
    		</select>
		</p>
		<?php  
		  $wrapper = '<div id="json_data" style="display: none">';
            $caption = '<caption class="%s">
                            <p class="cycle">%s / %s</p>
                            <ul>
                                <li>Total : %d DH</li>
                            </ul>
                        </caption>';
            $thaed = '<thead>
                        <tr>
                            <th>N° d\'Apt</th>
                            <th>Propriétaire</th>
                            <th>Côtisations</th>
                        </tr>
                        </thead>';
            $tbody = '<tr>
                         <td>Apt : %d</td>
                         <td>%s</td>
                         <td>%d DH</td>
                      </tr>
                            ';
            for($i = 1; $i <= count($result); $i++){  
                
                $arr = $result[$i-1];
                
                if($i%14 == 1){
                    //echo '<table>';
                    $wrapper.='<table>';
                    
                    $cycle = $arr['cycle'];
                    $date = date_create_from_format('Ym', $cycle);
                    $n = date_format($date, 'n');
                    //echo sprintf($caption, $months[$n-1], date_format($date, 'Y'), $s->getTotalDonation($cycle));
                    $wrapper.= sprintf($caption, $cycle, $months[$n-1], date_format($date, 'Y'), $s->getTotalDonation($cycle));
                    //echo $thaed;
                    $wrapper.= $thaed;
                    //echo '<tbody>';
                    $wrapper.='<tbody>';
                }
            
                $apt =  $arr['apt'];
                $name =  explode(' ', $arr['name'])[0];
                $status = $arr['status'] * 100;
                
                //echo sprintf($tbody, $apt, $name, $status);
                $wrapper.= sprintf($tbody, $apt, $name, $status);
                
                if($i%14 == 0){
                        //echo '</tbody>';
                        $wrapper.= '</tbody>';
                        //echo '</table>';
                        $wrapper.= '</table>';
                }  
            }
            $wrapper.='</div>';
            echo $wrapper;
            ?>
	</main>
	<aside id="aside">

		<div class="spends">

            <?php
            $h2 = '<h2>Détails des dépenses : <span class="green">%s</span> / %s</h2>';

                $spends = $sp->getLastSpends();
                $n = date('n', time());
                $y = date('Y', time());
                if(!empty($spends)){
                    $c = $spends[0]['cycle'];
                    $dt = date_create_from_format('Ym', $c);
                    $n = date_format($dt, 'n');
                    $y = date_format($dt, 'Y');
                    echo sprintf($h2, $months[$n -1], $y);
                    $li = '<li>%s ------ <span class="green">%d DH</span></li>';
                    echo '<ul>';
                    foreach ($spends as $arr) {
                        echo sprintf($li, $types[$arr['type']], $arr['amount']);
                    }
                    echo '</ul>';
                }else{
                    echo sprintf($h2, $months[$n -1], $y);
                    echo '<ul><li>Pas encore...</li></ul>';
                }

            ?>
        </div>
        <!-- <div class="descisions">
        	<h2>Décisions des réunions</h2>
        </div> -->
	</aside>
</body>
</html>



