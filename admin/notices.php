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
$months = array('Janvier', 'Fervrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');

if(isset($_POST['submit'])){
    
    date_default_timezone_set('Africa/Casablanca');
    $title = new Widgets(Widgets::TYPE_TITLE, $_POST['title'], 30);
    $txt = new Widgets(Widgets::TYPE_TXT, $_POST['body'], 250);
    
    if(!$title->isValid()){
        new Messages(Messages::TYPE_ERROR, 'Le titre doit contenir des alphabets seuls.');
    }
    if(!$txt->isValid()){
        new Messages(Messages::TYPE_ERROR, 'Ne depassez pas 250 caracteres de contenu.');
    }
    
    if($title->isValid() && $txt->isValid()){
        if($notices->addNotice($_POST)){
            new Messages(Messages::TYPE_SUCCESS, 'Enregistrement réussi.');
        }else{
            new Messages(Messages::TYPE_ERROR, 'Erreur : Enregistrement échoué.');
        }
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
    <title>Notices</title>
</head>
<body>
<?php require_once './inc/header.php';?>
<p class="definer">Notices</p>
<main>
	<?php
	$messages = '<p class="messages %s"><span>%s</span><i id="close">x</i></p>';
	$arr_messages = Messages::showMessages();
	if(!is_null($arr_messages[0])){
	    echo sprintf($messages, Messages::getType(), $arr_messages[0]);
	}
	$caption = '<caption>
                <p class="title">Tableau des Notices</p>
                <p class="cycle"><span class="green">%s </span>/ %s</p>
            </caption>';
	$thaed = '<thead>
            <tr class="simple">
                <th>Titre</th>
                <th>Contenu</th>
                <th>Date d\'édition</th>
            </tr>
            </thead>';
	$tbody = '
            <tr class="simple">
                <td>%s</td>
                <td class="body">%s</td>
                <td>%s</td>
                ';
	$n = $notices->show();
	$arr_cycles = array();
	foreach ($n as $arr) {
	    $arr_rows[] = $arr;
	    if(!in_array($arr['cycle'], $arr_cycles)){
	        $arr_cycles[] = $arr['cycle'];
	    }
	}

	
	for($i = 0; $i < count($arr_cycles); $i++){
	    echo '<table>';
	    $date = date_create_from_format('Ym', $arr_cycles[$i]);
	    $m =  date_format($date, 'n');
	    $y =  date_format($date, 'Y');
	    echo sprintf($caption, $months[$m -1], $y);
	    echo $thaed;
	    echo '<tbody>';
	    foreach ($n as $arr) {
	        if(in_array($arr_cycles[$i], $arr)){
	            $purified_title = preg_replace("#^'|'$#", "", stripslashes($arr['title']));
	            $purified_body = preg_replace("#^'|'$#", "", stripslashes($arr['body']));
	            echo sprintf($tbody, $purified_title, $purified_body, date('d-m-Y H:i',strtotime($arr['date_time'])));
	        }
	    }
	    echo '</tbody>';
	    echo '</table>';
	}
	?>

</main>
<aside id="aside">
    <section id="new_spend">
        <h2>Publier des Notices</h2>
        <form method="post">
            <p>
                <label for="title">Titre : </label>
                <input type="text" name="title" id="title">
            </p>
            <p>
                <label for="body">Contenu : </label>
                <textarea name="body" id="body" placeholder="Ecrire ici..."></textarea>
            </p>
            <input type="submit" name="submit" value="Publier">
        </form>
    </section>
</aside>
</body>
</html>