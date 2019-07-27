<div class="modal fade" id="process-recipe-subrecipe" tabindex="-1" role="dialog" aria-labelledby="process-recipe-subrecipe" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="process-recipe-subrecipe-title">Nový <?= (strcmp($filter["type"],"recipe") == 0)?"recept":"subrecept" ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a id="create-recipe-link" class="hidden" href="<?= (strcmp($filter["type"],"recipe") == 0)?site_url('recipe/create/recipe/'):site_url('recipe/create/subrecipe/') ?>"></a>
                <a id="update-recipe-link" class="hidden" href="<?= (strcmp($filter["type"],"recipe") == 0)?site_url('recipe/update/recipe/'):site_url('recipe/update/subrecipe/') ?>"></a>
                <a id="ingredient-details-link" class="hidden" href="<?= site_url('ingredient/load_ingredients_detail/') ?>"></a>
                <a id="conversion-unit-link" class="hidden" href="<?= site_url('unit/load_convt_for_unit/') ?>"></a>
                <a id="subrecipe-details-link" class="hidden" href="<?= site_url('recipe/load_subrecipes_detail/') ?>"></a>
                <a id="recipe-ingredient-items-link" class="hidden" href="<?= site_url('ingredient/load_recipe_ingredient_items/') ?>"></a>
                <a id="recipe-subrecipe-items-link" class="hidden" href="<?= site_url('recipe/load_recipe_subrecipe_items/') ?>"></a>
                <form id="process-recipe-subrecipe-form" action="<?= site_url('recipe') ?>" method="post" enctype="application/x-www-form-urlencoded">
                    <label for="recipe-name">Názov*</label>
                    <input id="recipe-name" class="form-control" type="text" name="recipe_name" maxlength="255" placeholder="<?= (strcmp($filter["type"],"recipe") == 0)?"Zadajte názov receptu":"Zadajte názov subreceptu" ?>">
                    <br/>
                    <label for="recipe-serving">Porcia*</label>
                    <input id="recipe-serving" class="form-control" type="text" name="recipe_serving" maxlength="255" placeholder="Zadajte porciu">
                    <br/>
                    <hr/>
                    <label for="recipe-ingredients">Ingrediencie*</label>
                    <div id="recipe-ingredients">
                        <div class="input-group">
                            <?= form_dropdown('recipe_ingredients[0]', $recipe_ingredients, '', 'class="form-control recipe-ingredients"'); ?>
                            <div class="form-vertical-space"></div>
                            <input class="form-control ingredients-quantity" type="text" name="ingredients_quantity[0]" placeholder="Množstvo">
                            <div class="form-vertical-space"></div>
                            <select class="form-control ingredients_units" name="ingredients_units[0]">
                                <option value="x">Zvoľte si jednotku</option>
                            </select>
                            <input type="hidden" name="ingredient_base_price">
                            <input type="hidden" data-base-unit-label="" name="ingredient_base_unit">
                            <input type="hidden" name="ingredient_unit_convt" value="1">
                            <div class="form-vertical-space"></div>
                            <input class="form-control ingredient-price" name="ingredient_price[0]" type="text" maxlength="255" placeholder="0€" readonly>
                            <div class="input-group-append">
                                <button class="custom-button remove-ingredient-from-recipe" type="button">
                                    <i class="fas fa-trash-alt" style="color:#dc3545"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button id="add-ingredient-for-recipe" class="btn btn-outline-success" type="button">Pridať</button>
                    <div id="recipe-ingredients-errors"></div>
                    <br/>
                    <hr/>
                    <label for="recipe-subrecipes">Subrecepty*</label>
                    <div id="recipe-subrecipes">
                        <div class="input-group">
                            <?= form_dropdown('recipe_subrecipes[0]', $recipe_subrecipes, '', 'class="form-control recipe-subrecipes"'); ?>
                            <div class="form-vertical-space"></div>
                            <input class="form-control subrecipes-quantity" name="subrecipes_quantity[0]" type="text" placeholder="Množstvo">
                            <div class="form-vertical-space"></div>
                            <select class="form-control subrecipe_units" name="subrecipe_units[0]">
                                <option value="x">Zvoľte si jednotku</option>
                            </select>
                            <input type="hidden" name="subrecipe_base_price">
                            <input type="hidden" data-base-unit-label="" name="subrecipe_base_unit">
                            <input type="hidden" name="subrecipe_unit_convt" value="1">
                            <div class="form-vertical-space"></div>
                            <input class="form-control subrecipe-price" name="subrecipe_price[0]" type="text" maxlength="255" placeholder="0€" readonly>
                            <div class="input-group-append">
                                <button class="custom-button remove-subrecipe-from-recipe" type="button">
                                    <i class="fas fa-trash-alt" style="color:#dc3545"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button id="add-subrecipe-for-recipe" class="btn btn-outline-success" type="button">Pridať</button>
                    <div id="recipe-subrecipes-errors"></div>
                    <br/>
                    <hr/>
                    <?= (strcmp($filter["type"], "recipe") == 0)?"":"<label for='recipe-unit'>Jednotka receptu*</label>".form_dropdown('recipe_unit', $subrecipe_units, '', 'class="form-control recipe_unit"'); ?>
                </form>
                <br/>
                <small class="form-text text">* - označuje povinné polia</small>
            </div>
            <div class="modal-footer">
                <button id="close-recipe" type="button" class="btn btn-outline-secondary" data-dismiss="modal">Zatvoriť</button>
                <button id="new-recipe" type="button" class="btn btn-outline-success">Vytvoriť</button>
                <button id="cancel-recipe" type="button" class="hidden btn btn-outline-secondary" data-dismiss="modal">Zrušiť</button>
                <button id="edit-recipe" data-ingredient-update-id="" type="button" class="hidden btn btn-outline-success">Zmeniť</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="remove-recipe" tabindex="-1" role="dialog" aria-labelledby="remove-recipe" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="remove-recipe-title">Odstrániť <?= (strcmp($filter["type"],"recipe") == 0)?"recept":"subrecept" ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Naozaj chcete odstrániť <?= (strcmp($filter["type"],"recipe") == 0)?"recept":"subrecept" ?> s názvom <span class="item-for-delete"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Zrušiť</button>
                <button id="delete-recipe" data-recipe-delete-id="" type="button" class="btn btn-outline-danger" data-dismiss="modal">Odstrániť</button>
                <a id="delete-recipe-link" class="hidden" href="<?= site_url() . "/recipe/delete/" ?>"></a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="info-recipe" tabindex="-1" role="dialog" aria-labelledby="info-recipe" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="info-recipe-title">Oznam</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="dialog-text"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Zatvoriť</button>
            </div>
        </div>
    </div>
</div>