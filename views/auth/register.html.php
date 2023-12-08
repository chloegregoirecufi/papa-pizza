<?php //if ($auth::isAuth()) $auth::redirect('/') ?>
        
        <main class="container-form">
            <h1 class="title">Je créer mon compte</h1>
            <!-- on va afficher les erreurs s'il y en a -->
            <?php if ($form_result && $form_result->hasErrors()) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $form_result->getErrors()[0]->getMessage() ?>
                </div>
            <?php endif ?>


            <form class="auth-form" action="/login" method="POST">
                <div class="box-auth-input">
                    <label class="detail-description">Adresse email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Mot de passe</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" name="password_confirm">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Votre Nom</label>
                    <input type="texte" class="form-control" name="lastname">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Votre prénom</label>
                    <input type="texte" class="form-control" name="firstname">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Votre numéro de téléphone</label>
                    <input type="number" class="form-control" name="phone">
                </div>
                <button type="submit" class="call-action">S'inscrire</button>
            </form>
            <p class="header-description">J'ai déjà un compte, <a class="auth-link" href="/connexion">je me connecte</a></p>

        </main>




