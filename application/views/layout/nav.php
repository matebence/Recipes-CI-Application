<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a id="nav-logo" class="navbar-brand js-scroll-trigger" href="<?= base_url(); ?>"></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Možnosti
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ml-auto">
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger <?= (isset($ingredient))?htmlentities($ingredient):''; ?>" href="<?= site_url('ingredient') ?>">Ingrediencie</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger <?= (isset($recipe))?htmlentities($recipe):''; ?>" href="<?= site_url('recipe') ?>">Recepty</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger <?= (isset($recipe))?htmlentities($recipe):''; ?>" href="<?= site_url('recipe/subrecipes') ?>">Subrecepty</a>
                </li>
            </ul>
        </div>
    </div>
</nav>