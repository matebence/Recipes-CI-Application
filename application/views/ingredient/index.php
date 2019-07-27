<?= $this->load->view("layout/header", NULL, TRUE); ?>
<div class="wrapper">
    <div class="container-fluid">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-4">
                    <h2>Zoznam <b>ingrediencií</b></h2>
                </div>
                <div class="col-8">
                    <a href="#" class="btn btn-primary" id="new-ingredient-button">
                        <i class="fas fa-plus-square"></i>
                        <span>Nová ingrediencia</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="table-filter">
            <div class="row">
                <div class="col-12 search-options">
                    <div class="filter-group">
                        <form method="get" action="<?= site_url()."ingredient"; ?>">
                            <label id="search-term-name">Názov</label>
                            <input id="search-term-input" type="text" name="term" required maxlength="255" pattern="[a-zA-Z]+" placeholder="Ingrediencie ..." class="form-control">
                            <button type="submit" class="btn btn-primary" id="search-button">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                            <?= isset($_GET['term']) ? "<a href=".site_url().'ingredient?reset=1'."><span id='cancel-search'><i class='far fa-times-circle'></i></span></a>" : ''; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content table-responsive">
            <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."ingredient?order_by=ingredient_id&sort=asc": site_url()."ingredient?order_by=ingredient_id&sort=desc"; ?>">#</a></th>
                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."ingredient?order_by=name&sort=asc": site_url()."ingredient?order_by=name&sort=desc"; ?>">Názov</a></th>
                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."ingredient?order_by=price&sort=asc": site_url()."ingredient?order_by=price&sort=desc"; ?>">Cena</a></th>
                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."ingredient?order_by=quantity&sort=asc": site_url()."ingredient?order_by=quantity&sort=desc"; ?>">Množstvo</a></th>
                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."ingredient?order_by=unit_id&sort=asc": site_url()."ingredient?order_by=unit_id&sort=desc"; ?>">Jednotka</a></th>
                    <th><a>Možnosti</a></th>
                </tr>
            </thead>
                <tbody>
                <?php foreach($results as $ingredient): ?>
                    <tr>
                        <td><?= htmlentities($ingredient["ingredient_id"]) ?></td>
                        <td class="ingredient-name"><?= htmlentities($ingredient["name"]) ?></td>
                        <td class="ingredient-price"><?= htmlentities($ingredient["price"]) ?>€</td>
                        <td class="ingredient-quantity"><?= htmlentities($ingredient["quantity"]) ?></td>
                        <td ><?= htmlentities($ingredient["label"]) ?></td>
                        <td style="display: none"><?= htmlentities($ingredient["base_unit"]) ?></td>
                        <td class="ingredient-unit-id" style="display: none"><?= htmlentities($ingredient["unit_id"]) ?></td>
                        <td>
                            <a id="<?= htmlentities($ingredient["ingredient_id"]) ?>" href="#" class="view edit-ingredient-button" title="View Details">
                                <i class="fas fa-edit" style="color:#17a2b8"></i>
                            </a>
                            <a id="<?= htmlentities($ingredient["ingredient_id"]) ?>" data-ingredient-name="<?= htmlentities($ingredient["name"]) ?>" href="#"  class="view delete-ingredient-button" title="View Details">
                                <i class="fas fa-trash-alt" style="color:#dc3545"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="clearfix">
            <?= paginate_links($filter['per_page'], $total_results, "ingredient") ?>
        </div>
    </div>
    </div>
</div>
<?= $this->load->view("ingredient/dialog/process", NULL, TRUE); ?>
<?= $this->load->view("layout/footer", NULL, TRUE); ?>