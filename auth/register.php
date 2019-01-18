<?php
session_start();
if(isset($_SESSION['pin'])){
    header('location: ../index.php');
    exit();
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
    <title>REGISTER</title>
</head>
<body>
<main>
<h1><small>Residence</small><br>AL IKHOUA WEB APP</h1>
	<form method="post">  
    <p>

    	<?php
    	spl_autoload_register('load');
    	function load($class){
    	    require '../class/'.$class.'.php';
    	}
    	$pdo = Database::getInstance();
    	$p = new Property($pdo);
    	$apt = $p->getUnregistredApt();
        
        if(isset($_POST['submit'])){
            $select = new Widgets(Widgets::TYPE_SELECT, $_POST['apt'], 1, $apt);
            $pin = new Widgets(Widgets::TYPE_PASS, $_POST['pin'], 6);
            $email = new Widgets(Widgets::TYPE_EMAIL, $_POST['email'], 1);

            if(!$select->isValid()){
                new Messages(Messages::TYPE_ERROR, 'Sélectionnez un N° d\'Apt.');
            }
            if(!$pin->isValid()){
                new Messages(Messages::TYPE_ERROR, 'Saisissez six chiffres ou changer de PIN.');
            }
            if(!$email->isValid()){
                new Messages(Messages::TYPE_ERROR, 'Adresse Email invalide.');
            }
            //var_dump(Messages::showMessages());
            if(is_null(Messages::showMessages())){
                if($p->register($_POST) ){
                    new Messages(Messages::TYPE_SUCCESS, 'Enregistrement réussi.');
                    $apt = $p->getUnregistredApt();
                    echo '<meta http-equiv="refresh" content="3;login.php" />';
                }else{
                    new Messages(Messages::TYPE_ERROR, 'Enregistrement échoué.');
                }
            }

        }

        $messages = '<p class="messages %s"><span>%s</span><i id="close">x</i></p>';
        $arr_messages = Messages::showMessages();
        if(!is_null($arr_messages[0])){
            echo sprintf($messages, Messages::getType(), $arr_messages[0]);
        }
        echo '<label for="apt">Numéro d\'appartement :</label>';
        echo '<select name="apt" id="apt"><option value="0"></option>';
        foreach ($apt as $value){
            echo '<option value="'.$value.'">Appartement '.$value.'</option>';
        }
        echo '</select>';
        ?>
    </p>
    <p>
    	<label for="pin">Votre PIN :</label>
    	<input type="password" name="pin" id="pin" size="6" required>
    </p>    
	<p>
		<label for="email">Votre adresse Courriel :</label>
    	<input type="email" name="email" id="email" size="30">
	</p>
    <input type="submit" name="submit" value="Valider">
</form>
</main>
<aside>
    <article>
    <h2>Enregistrer mon compte</h2>
    	<p>
    	Suivez les étapes suivantes :
    		<ol>
        		<li>Sélectionnez le numéro de votre appartement depuis la liste donnée.</li>
        		<li>Saisissez votre PIN qui doit contenir 6 chiffres de longueur.</li>
        		<li>Saisissez votre courriel (Email) pour être notifié de chaque mise à jour, nouvelle notice et aussi pour récupérer votre PIN si perdu.</li>
    		</ol>
    	</p>
    </article>
</aside>
</body>
</html>