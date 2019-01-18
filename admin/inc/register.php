<?php

//including Admin class
require_once('Admin.php');
$a = new Admin($pdo);

//On submit
if(isset($_POST['submit'])){
    
    $names = new Widgets(Widgets::TYPE_NAMES, $_POST['names'], 15);
    $user = new Widgets(Widgets::TYPE_USERNAME, $_POST['username'], 15);
    $pass = new Widgets(Widgets::TYPE_PASS, $_POST['pass'], 15);
    
    if(!$names->isValid()){
        new Messages(Messages::TYPE_ERROR, 'Nom et Prénom doivent contenir de l\'alphabet pure');
    }
    
    if(!$user->isValid()){
        new Messages(Messages::TYPE_ERROR, 'Le nom d\'utilisateur ne respecte pas les critères demandés.');
    }
    if(!$pass->isValid()){
        new Messages(Messages::TYPE_ERROR, 'Le mot de passe ne respecte pas les critères demandés.');
    }
        
    if ($user->isValid() && $pass->isValid()){
        $result = $a->register($_POST);
        //Testing result
        if($result){
            new Messages(Messages::TYPE_SUCCESS, 'Compte Administrateur enregistré. Redirection dans 3 secondes.');
            echo '<meta http-equiv="refresh" content="3;index.php" />';
        }else{
            new Messages(Messages::TYPE_ERROR, 'SYSTEM_ERROR_CAN\'T_REGISTER');
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
    <link rel="stylesheet" href="../css/login_admin.css">
    <script type="text/javascript" src='../js/alt.js'></script>
    <title>ADMINISTRATEUR</title>
</head>
<body>
<section>
	<p>
		<span>AL IKHOUA WEB APP</span><br/><br/>
		Création d'un compte Administrateur Unique.
	</p>
	<?php 
	$messages = '<p class="messages %s"><span>%s</span><i id="close">x</i></p>';
	$arr_messages = Messages::showMessages();
	if(!is_null($arr_messages[0])){
	    echo sprintf($messages, Messages::getType(), $arr_messages[0]);
	}
	?>
	<form method="post">
	<label for="names">Nom et Prénom :</label>
    <input type="text" name="names" id="names" required>
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" id="username" required>
    <label for="pass">Mot de passe :</label>
    <input type="password" name="pass" id="pass" required>
    <input type="submit" name="submit" value="Entrer">
</form>
</section>
</body>
</html>
