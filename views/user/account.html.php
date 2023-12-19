<?php use Core\Session\Session; ?>
<div class="admin-container">
    <h1 class="title">Mon compte </h1>
    <div class="box-admin">
       <div class="box-admin-content">
            <h2 class="sub-title">Mes information personnelles</h2>
            <div class="box-admin-link"><a href="/user/compte/<?= Session::get(Session::USER)->id ?>" class="call-action"><i class="bi bi-person-vcard"></i></a></div>
       </div>
       <div class="box-admin-content">
            <h2 class="sub-title">Mes commandes</h2>
            <div class="box-admin-link"><a href="/user/commandes/<?= Session::get(Session::USER)->id ?>" class="call-action"><i class="bi bi-card-checklist"></i></i></a></div>
       </div>
       <div class="box-admin-content">
            <h2 class="sub-title">Mes pizzas personnalisées</h2>
            <div class="box-admin-link"><a href="/user/pizza/list/<?= Session::get(Session::USER)->id ?>" class="call-action" ><img class="pizza-perso-svg" src="/assets/images/icon/pizza-perso.svg" alt="icone pizza perso"></a></div>
       </div>
       
    </div>
</div>