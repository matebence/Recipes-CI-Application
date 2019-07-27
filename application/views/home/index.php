<?= $this->load->view("layout/header", NULL, TRUE); ?>
<header class="masthead">
    <div class="wrapper">
        <div class="layer">
            <div class="container">
                <div class="search">
                    <div class="search-logo"><img src="<?php echo base_url() . "assets/img/"; ?>logo.png" alt="Logo" title="Logo"></div>
                    <div class="intro-search-input">
                        <form action="<?= site_url()."recipe" ?>" method="get" enctype="application/x-www-form-urlencoded">
                            <input class="search-input" type="text" name="term" required autofocus maxlength="255" pattern="[a-zA-Z]+" placeholder="Recepty, ingrediencie ...">
                            <span><i class="fas fa-search"></i></span>
                            <input class="search-button" type="submit" name="" value="Hľadať">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?= $this->load->view("layout/footer", NULL, TRUE); ?>