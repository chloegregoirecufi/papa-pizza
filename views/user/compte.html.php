<div class="admin-container">

    <h1 class="title">Mes informations personnelles</h1>
        <!-- on va afficher les erreurs s'il y en a -->
        <?php if ($form_result && $form_result->hasErrors()) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $form_result->getErrors()[0]->getMessage() ?>
            </div>
        <?php endif ?>


    <table class="table-user">
        <thead>
            <tr>
                <th class="footer-description">Nom</th>
                <th class="footer-description">Prenom</th>
                <th class="footer-description">Email</th>
                <th class="footer-description">Téléphone</th>
                <th class="footer-description">Adresse postale</th>
                <th class="footer-description">code postale</th>
                <th class="footer-description">ville</th>
                <th class="footer-description">Action</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td class="footer-description"><?= $user->lastname ?></td>
                    <td class="footer-description"><?= $user->firstname ?></td>
                    <td class="footer-description"><?= $user->email ?></td>
                    <td class="footer-description"><?= $user->phone ?></td>
                    <td class="footer-description"><?= $user->address ?></td>
                    <td class="footer-description"><?= $user->zip_code ?></td>
                    <td class="footer-description"><?= $user->city ?></td>
                    <td class="footer-description">
                        <a  class="button-delete" href="/user/compte/modification/<?= $user->id ?>"><i class="bi bi-pencil-square"></i></a>
                    </td>
                </tr>
        </tbody>
    </table>

</div>
