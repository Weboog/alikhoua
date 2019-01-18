<?php
session_start();
if(!isset($_SESSION['pass'])){
    header('location: ./auth/login.php');
    exit();
}
date_default_timezone_set('Africa/Casablanca');
spl_autoload_register('load');
function load($class){
    require '../class/'.$class.'.php';
}
$pdo = Database::getInstance();
$s = new Situation($pdo);
$spends = new Spends($pdo);
$months = array('Janvier', 'Fervrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');
$types = array(
        'Femme de ménage',
        'Facture d\'électricite',
        'Maintenance d\'ascenseur',
        'Réparations',
        'Changement d\'ampoules
        ');

$arr_spends_i = array();
$sp = $spends->getTypes();
for($i = 0; $i < count($sp); $i++){
    $arr_spends_i[] = $sp[$i][0];
}

if(isset($_POST['submit'])){
    date_default_timezone_set('Africa/Casablanca');
    $spend_type = new Widgets(Widgets::TYPE_SELECT, $_POST['type'], 1, $arr_spends_i);
    $amount = new Widgets(Widgets::TYPE_NUMBER, $_POST['amount'], 1);
    if($amount->isValid() and $spend_type->isValid()){
        if($spends->addSpend($_POST, date('Y/m/d H:i:s', time()))){
            new Messages(Messages::TYPE_SUCCESS, 'Enregistrement réussi.');
        }else{
            new Messages(Messages::TYPE_ERROR, 'Erreur : Enregistrement échoué.');
        }
    }else{
        new Messages(Messages::TYPE_ERROR, 'ERROR_SYSTEM_VIOLATION.');
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
    <title>Dépenses</title>
</head>
<body>
    <?php require_once 'inc/header.php'; ?>
    <p class="definer">Dépenses</p>
    <main>
        <?php
        $messages = '<p class="messages %s"><span>%s</span><i id="close">x</i></p>';
        $arr_messages = Messages::showMessages();
        if(!is_null($arr_messages[0])){
            echo sprintf($messages, Messages::getType(), $arr_messages[0]);
        }
        
        $caption = '<caption>
                <p class="title">Tableau des dépenses</p>
                <p class="cycle"><span class="green">%s </span>/ %s</p>
                <ul>
                    <li>Total : <span class="green">%.2f DH</span></li>
                </ul>
            </caption>';
        $thaed = '<thead>
            <tr class="simple">
                <th>Type de dépense</th>
                <th>Montant facturé en DH</th>
                <th>Date de parution</th>
            </tr>
            </thead>';
        $tbody = '
            <tr class="simple">
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>         
                ';
        $show = $spends->show();
        $arr_cycles = array();
        foreach ($show as $arr) {
            $arr_rows[] = $arr;
            if(!in_array($arr['cycle'], $arr_cycles)){
                $arr_cycles[] = $arr['cycle'];
            }
        }

        for($i = 0; $i < count($arr_cycles); $i++){
            echo '<table>';
            $c = $spends->getTotalSpends($arr_cycles[$i]);
            $date = date_create_from_format('Ym', $arr_cycles[$i]);
            $m =  date_format($date, 'n');
            $y =  date_format($date, 'Y');
            echo sprintf($caption, $months[$m -1], $y, $c);
            echo $thaed;
            echo '<tbody>';

            foreach ($show as $arr) {
                if(in_array($arr_cycles[$i], $arr)){
                    echo sprintf($tbody, $types[$arr['type']-1], $arr['amount'], date('d-m-Y',strtotime($arr['date_time'])));
                }
            }
            echo '</tbody>';
            echo '</table>';
        }
        ?>

    </main>
    <aside id="aside">
        <section id="new_spend">

            <h2>Enregister des dépenses</h2>
            <form method="post">
                <p><label for="type">Types de dépense : </label>
                    <select name="type" id="type">
                        <?php
                        $sp = $spends->getTypes();
                        foreach ($sp as $arr) {
                            echo '<option value="'.$arr[0].'">'.$types[$arr[0]-1].'</option>';
                        };
                        ?>
                    </select>
                </p>
                <p>
                    <label for="amount">Montant facturé :</label>
                    <input type="text" name="amount" id="amount" placeholder="Montant ici...">
                </p>
                <input type="submit" name="submit" value="Enregistrer">
            </form>
        </section>
    </aside>
</body>
</html>