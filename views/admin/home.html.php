<h1 class="title">Back office </h1>
<div class="box-ingredient-detail">
<?php foreach ($user as $user) : ?>
    <p class="detail-description"><?= $user->label ?></p>
<?php endforeach ?>

</div>