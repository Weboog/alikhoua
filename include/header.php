<header>
    <div id="id">
        <p class="names">
            Propriétaire Mr :
            <span class="green">
                <?php
                echo isset($_SESSION['pin']) ? $_SESSION['name'] : '';
                ?>
            </span>
        </p>
        <p class="disconnect"><a href="disconnect.php">Déconnexion</a></p>
    </div>
    <div id="perso">
    	<h6>Situation perso</h6>
    	<?php 
    	   $stats = $s->getPersonalStats($_SESSION['apt']);
    	   if(!is_null($stats['last_cycle'])){
    	       $dt = date_create_from_format('Ym', $stats['last_cycle']);
    	       $m = date_format($dt, 'n');
    	       $y = date_format($dt, 'Y');
    	       $month = $months[$m-1];
    	   }else{
    	       $month = '-';
    	       $y = '-';
    	   }
    	   $perso = '
                    <ul>
                		<li>Total des côtisations : <span class="green">%d DH</span></li>
                		<li>Total des mois : <span class="green">%d</span></li>
                		<li>Dernier mois en règle : <span class="green"> %s / %s</span></li>
            	    </ul>
                    ';
    	   echo sprintf($perso, $stats['total_sum'], $stats['total_cycles'], $month, $y);
    	?>
    	
    </div>
    <div id="status">
    	<?php 
        	if(strtotime($stats['last_cycle']) == strtotime(date('Ym'))){
        	    echo '<i class="far fa-check-circle"></i><br><br>Situation : à jour';
        	}else{
        	    echo '<i class="fas fa-exclamation-triangle"></i><br><br>Situation : en attente';
        	}
    	?>
    </div>
    <div id="notify">
        <?php 
        	if(strtotime($stats['last_cycle']) != strtotime(date('Ym'))){
        	    //echo '<p><a href="#">Notifier avoir déposer la côtisation</a></p>';
        	}
        ?>	
    </div>
</header>
<p class="label">Informations générales</p>
<div id="statistics">
    <article>
        <h2>Solde de caisse</h2>
        <?php

        $h6 = '<h6>%s</h6>';
        echo sprintf($h6, date('m/Y', strtotime($s->getLastCycle()['c'])));
        $p = '<p>%.2f <small>DH</small></p>';
        echo sprintf($p, $s->getBalance());
        ?>
    </article>
    <article>
        <h2>Total des dépenses</h2>
        <?php
        $sp = new Spends($pdo);
        $h6 = '<h6>%s</h6>';
        echo sprintf($h6, date('m/Y'));
        $p = '<p>%.2f <small>DH</small></p>';
        echo sprintf($p, $sp->getTotalSpends(date('Ym')));
        ?>
    </article>
    
    <article>
        <h2>Extras</h2>
        <?php
        $ex = new Extras($pdo);
        $last_ex = $ex->getLastExtras();
        $h6 = '<h6>%s</h6>';
        echo sprintf($h6, date('m/Y', strtotime($last_ex['cycle'])));
        $p = '<p>%s <small>DH</small></p>';
        echo sprintf($p, $last_ex['amount']);
        ?>
    </article>
</div>