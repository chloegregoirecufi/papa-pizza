<div class="admin-container">

    <h1 class="title">Les clients</h1>
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
                <th class="footer-description">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td class="footer-description"><?= $user->lastname ?></td>
                    <td class="footer-description"><?= $user->firstname ?></td>
                    <td class="footer-description"><?= $user->email ?></td>
                    <td class="footer-description"><?= $user->phone ?></td>
                    <td class="footer-description">
                        <a onclick="return confirm('Etes-vous certain de vouloir supprimer ?')" class="button-delete" href="/admin/user/delete/<?= $user->id ?>"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</div>
