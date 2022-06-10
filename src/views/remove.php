<?php
require __DIR__ . '/top.php';

?>
<main class="main saskaitos">
<h3>Nuskaičiuoti lėšas</h3>
<?php
    require __DIR__ . '/msg.php';
    ?>

    <form action="" method="post" class="new add">
        <span>Vardas: <span class="value"></span><?= $users['name']?></span>
        <span>Pavarde: <span class="value"></span><?= $users['surname']?></span>
        <span>Sąskaitos likutis: <span class="value"></span><?= $users['suma']?></span>
        <span>Nuskaičiuojama suma: <input type="number" name="remove" required></span>
        <button type="submit" class="btn">CASH OUT</button>
    </form>

    <?php
    require __DIR__ . '/bottom.php';
    ?>