<?php
session_start();
if(!isset($_SESSION['pass'])){
    header('location: donations.php');
    exit();
}
spl_autoload_register('load');
function load($class){
    require '../class/'.$class.'.php';
}
$pdo = Database::getInstance();
$s = new Situation($pdo);
$spends = new Spends($pdo);
$notices = new Notice($pdo);
$ex = new Extras($pdo);
$months = array('Janvier', 'Fervrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');
$types = array('Reunion', 'Conseil');
if(isset($_POST['submit']) and $_POST['type'] == '1'){
    date_default_timezone_set('Africa/Casablanca');
    if($ex->updateExtras(date('Ym', time()))){
        new Messages(Messages::TYPE_SUCCESS, 'Enregistrement réussi.');
    }else{
        new Messages(Messages::TYPE_ERROR, 'Erreur : Enregistrement échoué.');
    }
}
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
    <script type="text/javascript" src='../js/alt.js'></script>
    <title>Extras</title>
</head>
<body>
<?php require_once './inc/header.php';?>
<p class="definer">Extras</p>
<main>
	<?php
	$messages = '<p class="messages %s"><span>%s</span><i id="close">x</i></p>';
	$arr_messages = Messages::showMessages();
	if(!is_null($arr_messages[0])){
	    echo sprintf($messages, Messages::getType(), $arr_messages[0]);
	}
	$caption = '<caption>
                <p class="title">Situation des Extras</p>
            </caption>';
	$thaed = '<thead>
            <tr class="simple">
                <th>Mois d\'effet</th>
                <th>Montant</th>
            </tr>
            </thead>';
	$tbody = '
            <tr class="simple">
                <td>%s / %s</td>
                <td>%s DH</td>
                ';
	
	$x = $ex->show();
	$arr_cycles = array();
	echo '<table>';
	echo $caption;
	echo $thaed;
	echo '<tbody>';
	foreach ($x as $arr) {
	    $arr_rows[] = $arr;
	    if(!in_array($arr['cycle'], $arr_cycles)){
	        $arr_cycles[] = $arr['cycle'];
	    }
	    $date = date_create_from_format('Ym', $arr['cycle']);
	    $m = date_format($date, 'n');
	    $y = date_format($date, 'Y');
	    echo sprintf($tbody, $months[$m-1], $y, $arr['amount']*Extras::$AMOUNT);
	}
	echo '</tbody>';
	echo '</table>';
	?>
</main>
<aside id="aside">
    <section id="new_spend">
        <h2>Ajouter un extras</h2>
        <form method="post">
            <?php
                $span = '<span class="green">Extra déja fait pour ce mois-ci.</span>';
                $form = '<p>
                            <label for="type">Ajouter l\'extras : </label>
                            <select name="type" id="type" >
                                <option value="0">NON</option>
                                <option value="1">OUI</option>
                            </select>
                        </p>
                        <input type="submit" name="submit" value="Ajouter">';
                echo (!is_null($x[0]) and $x[0]['amount'] == 1) ? $span : $form;

            ?>


        </form>
    </section>
</aside>
</body>
</html>