    <h1 class="title title-detail"><?= $pizza->name ?></h1>
    <div class="container-pizza-detail">
        <div class="box-img-detail">
            <img class="image-detail" src="/assets/images/pizza/<?= $pizza->image_path ?>" 
            alt="image de <?= $pizza->name ?>">
            <div class="allergene">
                <!-- TODO:gerer l'affichage des allergene s'il y en a  -->
                <?php if(in_array(true, array_column($pizza->ingredients, 'is_allergic'))) : ?>
                        <h3 class="sub-title-allergene">*Allergène :</h3>
                        <!-- j'affiche le label de l'ingredient -->
                        <?php foreach($pizza->ingredients as $ingredient) : ?>
                            <?php if($ingredient->is_allergic): ?>
                                <span class="detail-description"><?= $ingredient->label ?></span>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php endif ?>
            </div>
        </div>
        <div class="info-pizza-detail">
            <h3 class="sub-title sub-title-detail">ingrédients:</h3>
            <div class="box-ingredient-detail">
                <?php foreach ($pizza->ingredients as $ingredient) : ?>
                    <p class="detail-description"><?= $ingredient->label ?></p>
                <?php endforeach ?>
            </div>
            <h3 class="sub-title sub-title-detail">Nos prix :</h3>
            <table>
                <thead>
                    <tr>
                        <th class="footer-description">Taille</th>
                        <th class="footer-description">Prix</th>
                        <th class="footer-description"><i class="bi bi-basket2"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pizza->prices as $price): ?>
                        <tr>
                            <td class="footer-description"><?= $price->size->label ?></td>
                            <td class="footer-description"><?= number_format($price->price, 2, ',', ' ') ?> €</td>
                            <td class="footer-description"><i class="bi bi-plus-circle"></i></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                    
            </table>
        </div>
    </div>





   
    



