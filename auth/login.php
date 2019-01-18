<?php
session_start();
if(isset($_SESSION['pin'])){
    header('location: ../index.php');
    exit();
}
spl_autoload_register('load');
function load($class){
    require '../class/'.$class.'.php';
}

//Creating database Instance
$pdo = Database::getInstance();

//Create new cycle
$s = new Situation($pdo);
$s->createCycle();

//On submit
if(isset($_POST['submit'])){

    $pin = new Widgets(Widgets::TYPE_PIN, $_POST['pin'], 6);

    if($pin->isValid()){
        $p = new Property($pdo);
        $result = $p->login($_POST['pin']);
        if($result){
            $_SESSION['apt'] = $result['apt'];
            $_SESSION['pin'] = $result['pin'];
            $_SESSION['name'] = $result['name'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['phone'] = $result['phone'];
            header('location: ../index.php');
        }else{
            new Messages(Messages::TYPE_ERROR, 'Compte inéxistant.');
        }
    }else{
        new Messages(Messages::TYPE_ERROR, 'Saisissez six chiffres.');
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
    <link rel="stylesheet" href="../css/login.css">
    <script type="text/javascript" src='../js/alt.js'></script>
    <title>LOGIN</title>
</head>
<body>
<main>
	<h1><small>Résidence</small><br>AL IKHOUA WEB APP</h1>
	<form method="post">
	<p>
		<?php 
		$messages = '<p class="messages %s"><span>%s</span><i id="close">x</i></p>';
		$arr_messages = Messages::showMessages();
		if(!is_null($arr_messages[0])){
		    echo sprintf($messages, Messages::getType(), $arr_messages[0]);
		}
		?>
	</p>
        <label for="pin">Saisissez votre PIN :</label>
        <input type="password" name="pin" id="pin" size="6" required>
        <p><!-- <a href="">J'ai oublié mon PIN</a> --></p>
        <input type="submit" name="submit" value="Entrer">
	</form>
	<section>
		<h2>Pas encore inscrit ?</h2>
		<p><a href="register.php">Enregistrer mon compte</a></p>
	</section>
</main>
<aside>
<article>
	<h2>Espace communautaire de la résidence AL IKHOUA</h2>
	<p>Cet espace fourni la totalité des informations et évênements relatifs a la résidence AL IKHOUA.
		Du fait l'accès est strictement interdit au non propriétaires et toutes personnes étrangères.
	</p>
</article>
</aside>
</body>
</html>