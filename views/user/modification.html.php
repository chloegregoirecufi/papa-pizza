 <main class="container-form">
            <h1 class="title">Modifier mes information personnelles</h1>
            <!-- on va afficher les erreurs s'il y en a -->
            <?php if ($form_result && $form_result->hasErrors()) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $form_result->getErrors()[0]->getMessage() ?>
                </div>
            <?php endif ?>

            <!-- méthode post pour sécuriser le formulaire  -->
            <form class="auth-form" action="/register-modification/<?= $user->id ?>" method="POST">
                <input type="hidden" name="id" value="<?= $user->id ?>">
                <div class="box-auth-input">
                    <label class="detail-description">Nom</label>
                    <input type="texte" class="form-control" name="lastname" value="<?php echo $user->lastname ; ?>">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Prénom</label>
                    <input type="texte" class="form-control" name="firstname" value="<?php echo $user->firstname ; ?>">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Email</label>
                    <input type="texte" class="form-control" name="email" value="<?php echo $user->email ; ?>">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Votre numéro de téléphone</label>
                    <input type="number" class="form-control" name="phone" value="<?php echo $user->phone ; ?>">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Adresse postale</label>
                    <input type="texte" class="form-control" name="address" value="<?php echo $user->address ; ?>">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Code postale</label>
                    <input type="texte" class="form-control" name="zip_code" value="<?php echo $user->zip_code ; ?>">
                </div>
                <div class="box-auth-input">
                    <label class="detail-description">Ville</label>
                    <input type="texte" class="form-control" name="city" value="<?php echo $user->city ; ?>">
                </div>
                <button type="submit" class="call-action">Enregistrer mes modifications</button>
            </form>
        </main>




