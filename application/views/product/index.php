<?php foreach($products as $p): ?>
    <h3><?= $p->name ?></h3>
    <p><?= $p->price ?></p>
<?php endforeach; ?>