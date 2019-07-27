<div class="modal fade" id="process-ingredient" tabindex="-1" role="dialog" aria-labelledby="process-ingredient" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="process-ingredient-title">Nová ingrediencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a id="create-ingredient-link" class="hidden" href="<?= site_url('ingredient/create/') ?>"></a>
                <a id="update-ingredient-link" class="hidden" href="<?= site_url('ingredient/update/') ?>"></a>
                <a id="ingredient-units-link" class="hidden" href="<?= site_url('unit/load_units_by_base_unit/') ?>"></a>
                <form id="process-ingredient-form" action="<?= site_url('ingredient') ?>" method="post" enctype="application/x-www-form-urlencoded">
                    <label for="ingredient-name">Názov*</label>
                    <input id="ingredient-name" class="form-control" type="text" name="ingredient_name" maxlength="255" placeholder="Zadajte názov ingrediencie">
                    <br/>
                    <label for="ingredient-price">Cena*</label>
                    <input id="ingredient-price" class="form-control" type="text"  name="ingredient_price" pattern="[0-9]+([\.,][0-9]+)?" placeholder="Zadajte cenu ingrediencie">
                    <br/>
                    <label for="ingredient-quantity">Množstvo*</label>
                    <input id="ingredient-quantity" class="form-control" type="text" name="ingredient_quantity" pattern="[0-9]+([\.,][0-9]+)?" placeholder="Zadajte množstvo ingrediencie">
                    <br/>
                    <label for="ingredient-unit">Jednotka*</label>
                    <?= form_dropdown('ingredient_unit', $ingredient_units, '',"class=form-control id=ingredient-unit"); ?>
                </form>
                <br/>
                <small class="form-text text">* - označuje povinné polia</small>
            </div>
            <div class="modal-footer">
                <button id="close-ingredient" type="button" class="btn btn-outline-secondary" data-dismiss="modal">Zatvoriť</button>
                <button id="new-ingredient" type="button" class="btn btn-outline-success">Vytvoriť</button>
                <button id="cancel-ingredient" type="button" class="hidden btn btn-outline-secondary" data-dismiss="modal">Zrušiť</button>
                <button id="edit-ingredient" data-ingredient-update-id="" type="button" class="hidden btn btn-outline-success">Zmeniť</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="remove-ingredient" tabindex="-1" role="dialog" aria-labelledby="remove-ingredient" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="remove-ingredient-title">Odstrániť ingredienciu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Naozaj chcete odstrániť ingredienciu s názvom <span class="item-for-delete"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Zrušiť</button>
                <button id="delete-ingredient" data-ingredient-delete-id="" type="button" class="btn btn-outline-danger" data-dismiss="modal">Odstrániť</button>
                <a id="delete-ingredient-link" class="hidden" href="<?= site_url() . "/ingredient/delete/" ?>"></a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="info-ingredient" tabindex="-1" role="dialog" aria-labelledby="info-ingredient" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="info-ingredient-title">Oznam</h5>
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