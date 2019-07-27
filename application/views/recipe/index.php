<?= $this->load->view("layout/header", NULL, TRUE); ?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-4">
                            <h2>Zoznam
                                <b><?= (strcmp($filter["type"],"recipe") == 0)?"receptov":"subreceptov" ?></b>
                            </h2>
                        </div>
                        <div class="col-8">
                            <a href="#" class="btn btn-primary" id="new-recipe-button">
                                <i class="fas fa-plus-square"></i>
                                <span>Nový <?= (strcmp($filter["type"],"recipe") == 0)?"recept":"subrecept" ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-filter">
                    <div class="row">
                        <div class="col-12 search-options">
                            <div class="filter-group">
                                <form method="get" action="<?= (strcmp($filter["type"],"recipe") == 0)?site_url()."recipe":site_url()."recipe/subrecipes" ?>">
                                    <label id="search-term-name">Názov</label>
                                    <input id="search-term-input" type="text" name="term" required maxlength="255" pattern="[a-zA-Z]+" placeholder="<?= (strcmp($filter["type"],"recipe") == 0)?"Recepty":"Subrecepty" ?> ..." value="<?= isset($_GET["term"])?$_GET["term"]:"" ?>" class="form-control">
                                    <button type="submit" class="btn btn-primary" id="search-button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </form>
                                <?= isset($_GET["term"])?(strcmp($filter["type"],"recipe") == 0)?"<a href=".site_url().'recipe?reset=1'."><span id='cancel-search'><i class='far fa-times-circle'></i></span></a>":"<a href=".site_url().'/recipe/subrecipes?reset=1'."><span id='cancel-search'><i class='far fa-times-circle'></i></span></a>":"" ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <?php if(strcmp($filter["type"],"recipe") == 0): ?>
                                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."recipe?order_by=recipe_id&sort=asc": site_url()."recipe?order_by=recipe_id&sort=desc" ?>">#</a></th>
                                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."recipe?order_by=name&sort=asc": site_url()."recipe?order_by=name&sort=desc" ?>">Názov</a></th>
                                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."recipe?order_by=price&sort=asc": site_url()."recipe?order_by=price&sort=desc" ?>">Cena</a></th>
                                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."recipe?order_by=serving&sort=asc": site_url()."recipe?order_by=serving&sort=desc" ?>">Porcia</a></th>
                                    <th><a>Možnosti</a></th>
                                <?php else: ?>
                                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."recipe/subrecipes?order_by=recipe_id&sort=asc": site_url()."recipe/subrecipes?order_by=recipe_id&sort=desc" ?>">#</a></th>
                                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."recipe/subrecipes?order_by=name&sort=asc": site_url()."recipe/subrecipes?order_by=name&sort=desc" ?>">Názov</a></th>
                                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."recipe/subrecipes?order_by=price&sort=asc": site_url()."recipe/subrecipes?order_by=price&sort=desc" ?>">Cena</a></th>
                                    <th><a href="<?= ((isset($_GET["sort"]) && (strcmp($_GET["sort"], "desc") == 0))) ? site_url()."recipe/subrecipes?order_by=serving&sort=asc": site_url()."recipe/subrecipes?order_by=serving&sort=desc" ?>">Porcia</a></th>
                                    <th><a>Možnosti</a></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($results as $recipe): ?>
                            <tr>
                                <td><?= htmlentities($recipe["recipe_id"]) ?></td>
                                <td class="recipe-name"><?= htmlentities($recipe["name"]) ?></td>
                                <td class="recipe-price"><?= htmlentities($recipe["price"]) ?>€</td>
                                <td class="recipe-serving"><?= htmlentities($recipe["serving"]) ?></td>
                                <td class="recipe-unit_id hidden"><?= htmlentities($recipe["unit_id"]) ?></td>
                                <td>
                                    <a id="<?= htmlentities($recipe["recipe_id"]) ?>" href="#" class="view edit-recipe-button" title="View Details">
                                        <i class="fas fa-edit" style="color:#17a2b8"></i>
                                    </a>
                                    <a id="<?= htmlentities($recipe["recipe_id"]) ?>" data-recipe-name="<?= htmlentities($recipe["name"]) ?>" href="#"  class="view delete-recipe-button" title="View Details">
                                        <i class="fas fa-trash-alt" style="color:#dc3545"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="clearfix">
                    <?= paginate_links($filter['per_page'], $total_results, "recipe") ?>
                </div>
            </div>
        </div>
    </div>
<?= $this->load->view("recipe/dialog/process", NULL, TRUE); ?>
<?= $this->load->view("layout/footer", NULL, TRUE); ?>