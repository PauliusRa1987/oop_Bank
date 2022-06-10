<?php 

if(!empty($messages)) : ?>
<div>
    <?php foreach($messages as $message) : ?>
        <h3 class="<?= $message['type'] ?>"><?= $message['msg'] ?></h3>
    <?php endforeach ?>
</div>
<?php endif ?>