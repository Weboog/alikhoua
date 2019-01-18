<?php
session_start();
if(isset($_SESSION['pass'])){
    header('location: donations.php');
    exit();
}
spl_autoload_register('load');
function load($class){
    require '../class/'.$class.'.php';
}

$pdo = Database::getInstance();
//including Admin class
require_once('Admin.php');
$s = new Situation($pdo);
$a = new Admin($pdo);
if(!$a->checkAdmin()){
    require './inc/register.php';
    exit();
}
//On submit
if(isset($_POST['submit'])){
    
    $user = new Widgets(Widgets::TYPE_USERNAME, $_POST['username'], 15);
    $pass = new Widgets(Widgets::TYPE_PASS, $_POST['pass'], 15);
    
    if(!$user->isValid()){
        new Messages(Messages::TYPE_ERROR, 'Le nom d\'utilisateur ne respecte pas les critères demandés.');
    }
    if(!$pass->isValid()){
        new Messages(Messages::TYPE_ERROR, 'Le mot de passe ne respecte pas les criteres demandés.');
    }
        
    if ($user->isValid() && $pass->isValid()){
        $result = $a->login($_POST);
        
        //Testing result
        if($result){
            $_SESSION['username'] = $result['username'];
            $_SESSION['pass'] = $result['pass'];
            $_SESSION['names'] = $result['names'];
            header('location: donations.php');
        }else{
            new Messages(Messages::TYPE_ERROR, 'Compte inéxistant');
        }
    }      
}
$s->createCycle();

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/login_admin.css">
    <script type="text/javascript" src='../js/alt.js'></script>
    <title>ADMINISTRATEUR</title>
</head>
<body>
<section>
	<p>
		<span>AL IKHOUA WEB APP</span><br/><br/>
		Zone d'administration restreinte veuillez vous identifiez
	</p>
	<?php 
	$messages = '<p class="messages %s"><span>%s</span><i id="close">x</i></p>';
	$arr_messages = Messages::showMessages();
	if(!is_null($arr_messages[0])){
	    echo sprintf($messages, Messages::getType(), $arr_messages[0]);
	}
	?>
	<form method="post">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" id="username" required>
    <label for="pass">Mot de passe :</label>
    <input type="password" name="pass" id="pass" required>
    <input type="submit" name="submit" value="Entrer">
</form>
</section>
</body>
</html>
