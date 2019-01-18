<?php
session_start();
if(!isset($_SESSION['pass'])){
    header('location: index.php');
}
spl_autoload_register('load');
function load($class){
    require '../class/'.$class.'.php';
}
$months = array('Janvier', 'Fervrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');
$pdo = Database::getInstance();
$s = new Situation($pdo);
if(isset($_POST['submit']) and isset($_POST['apt'])){
    $apts = $_POST['apt'];
    $returns = array();
    foreach ($apts as $apt){
        $explode = explode('|', $apt);
        $c = $explode[0];
        $a = $explode[1];
        $res = $s->changeStatus($a, $c, date('Y/m/d H:i:s', time()));
        if($res){
            new Messages(Messages::TYPE_SUCCESS, 'Enregistrement réussi.');
        }else{
            new Messages(Messages::TYPE_ERROR, 'Enregistrement échoué.');
        }
    }
}
ob_start();
$result = $s->show();
ob_get_contents();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/styles_admin.css">
    <script type="text/javascript" src='../js/main.js'></script>
    
    <title>Tableau de bord</title>
</head>
<body>
    <?php require_once 'inc/header.php'; ?>
    <p class="definer">Côtisations</p>
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
        $messages = '<p class="messages %s"><span>%s</span><i id="close">x</i></p>';
        $arr_messages = Messages::showMessages();
        if(!is_null($arr_messages[0])){
            echo sprintf($messages, Messages::getType(), $arr_messages[0]);
        }
        $wrapper = '<div id="json_data" style="display: none">';
        $caption = '<caption class="%s">
                <p class="title">Tableau des côtisations</p>
                <p class="cycle"><span class="green">%s </span>/ %s</p>
                <ul>
                    <li>Total : <span class="green">%d DH</span></li>
                    <li>Progression : <span class="green">%d&percnt;</span></li>
                </ul>
            </caption>';
        $thaed = '<thead>
            <tr>
                <th>N° d\'Apt</th>
                <th>Propriétaire</th>
                <th>Côtisations</th>
                <th><span class="green">Actions</span></th>
            </tr>
            </thead>';
        $tbody = '
            <tr>
                <td>Apt : %d</td>
                <td>%s</td>
                <td>%d DH</td>         
                ';
        $select = '<td>
                    <label for="done">côtisant : </label>
                    <select name="done" id="done" class="done" data-cycle="%d" data-apt="%d">
                        <option value="0">NON</option>
                        <option value="1">OUI</option>
                    </select>
                </td>
                ';
        $blank = '<td><span class="green">Aucune action requise</span></td>';
        //Extract Data from situations
        for($i = 1; $i <= count($result); $i++){

            $arr = $result[$i-1];

            if($i%14 == 1){
                //echo '<table>';
                $wrapper.= '<table>';
                $cycle = $arr['cycle'];
                $date = date_create_from_format('Ym', $cycle);
                $n = date_format($date, 'n');

                /* echo sprintf($caption,
                    $months[$n-1],
                    date_format($date, 'Y'),
                    $s->getTotalDonation($cycle),
                    $s->getProgress($cycle)['percent']
                    ); */
                $wrapper.= sprintf($caption,
                            $cycle,
                            $months[$n-1],
                            date_format($date, 'Y'),
                            $s->getTotalDonation($cycle),
                            $s->getProgress($cycle)['percent']
                            );
                //echo $thaed;
                $wrapper.= $thaed;
                //echo '<tbody>';
                $wrapper.= '<tbody>';
            }

            $apt =  $arr['apt'];
            $name =  explode(' ', $arr['name'])[0];
            $status = $arr['status'] * 100;

            //echo sprintf($tbody, $apt, $name, $status);
            $wrapper.= sprintf($tbody, $apt, $name, $status);
            if($arr['status'] == 0){
                //echo sprintf($select, $cycle, $apt);
                $wrapper.= sprintf($select, $cycle, $apt);
            }else{
                //echo $blank;
                $wrapper.= $blank;
            }
            echo '</tr>';


            if($i%14 == 0){
                //echo '</tbody>';
                $wrapper.= '</tbody>';
                //echo '</table>';
                $wrapper.= '</table>';
            }
        }
        echo $wrapper;
        ?>
    </main>

	<aside id="aside">

		<div>
            <h2>Opérations à enregistrer</h2>
            <form method="post" id="registerForm">
                <div class="elements"><p id="placeholder">Aucun...</p></div>
                <div class="buttons">
                    <input type="button" id="cancel" value="Annuler">
                    <input type="submit" name="submit" value="Enregistrer">
                </div>
            </form>
        </div>
	</aside>
</body>
</html>