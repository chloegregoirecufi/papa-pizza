<?php use Core\Session\Session; ?>
<div class="admin-container">
    <h1 class="title title-page">Votre panier</h1>
    <?php ?>
     <div class="d-flex justify-content-center"><a href="/user/compte/<?= Session::get(Session::USER)->id ?>"></a></div>
        <div class="d-flex flex-row flex-wrap my-3 justify-content-center col-lg-8 align-content-center">
                <div class="card m-2" style="width: 18rem;">
                    <div class="card-body">
                        <h3 class="card-title sub-title text-center"><?= $pizzas->name ?></h3>
                        <p class="card-description"><?= $pizzas->name ?></p>
                    </div>
                </div>
        </div>
    </div>
 </div>

