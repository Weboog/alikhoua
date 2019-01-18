<header>
    <div id="id">
        <p class="names">
            Admininstrateur :
            <span class="green">
                <?php
                echo isset($_SESSION['pass']) ? $_SESSION['names'] : '';
                ?>
            </span>
        </p>
        <p class="disconnect"><a href="disconnect.php">Déconnexion</a></p>
    </div>
    <nav>
        <ul>
            <li><a href="donations.php">Côtisations</a></li>
            <li><a href="spends.php">Dépenses</a></li>
            <li><a href="notices.php">Notices</a></li>
            <li><a href="extras.php">Extras</a></li>
        </ul>
    </nav>
    <div id="statistics">
        <article>
            <h2>Solde de caisse</h2>
            <?php

                $h6 = '<h6>%s</h6>';
                $dt = date_create_from_format('Ym', $s->getLastCycle()['c']);
                $m = date_format($dt, 'm');
                $y = date_format($dt, 'Y');
                echo sprintf($h6, $m.'/'.$y);
                $p = '<p>%1.2f <small>DH</small></p>';
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
            $dt = date_create_from_format('Ym', $last_ex['cycle']);
            $m = date_format($dt, 'm');
            $y = date_format($dt, 'Y');
            echo sprintf($h6, $m.'/'.$y);
            $p = '<p>%d <small>DH</small></p>';
            echo sprintf($p, $last_ex['amount']);
            ?>
        </article>
    </div>
    <div id="progress">
        <article>
            <h2>Progression du mois en cours...</h2>
            <p><?php echo $s->getLastProgress()['percent'].'%' ?></p>
        </article>

    </div>
</header>