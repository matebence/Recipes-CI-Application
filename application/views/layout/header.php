<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= (isset($title))?htmlentities($title):''; ?></title>
    <link rel="apple-touch-icon" sizes="57x57" href="<?= base_url() . "assets/favicon/"; ?>apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= base_url() . "assets/favicon/"; ?>apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= base_url() . "assets/favicon/"; ?>apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url() . "assets/favicon/"; ?>apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= base_url() . "assets/favicon/"; ?>apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= base_url() . "assets/favicon/"; ?>apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url() . "assets/favicon/"; ?>apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= base_url() . "assets/favicon/"; ?>apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url() . "assets/favicon/"; ?>apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?= base_url() . "assets/favicon/"; ?>android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url() . "assets/favicon/"; ?>favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url() . "assets/favicon/"; ?>favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() . "assets/favicon/"; ?>favicon-16x16.png">
    <link rel="manifest" href="<?= base_url() . "assets/favicon/"; ?>manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link href="<?= base_url() . "node_modules/bootstrap/dist/css/"; ?>bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() . "node_modules/fontawesome/css/"; ?>all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() . "assets/css/"; ?>recipes.css" rel="stylesheet" type="text/css">
</head>
<?= $this->load->view("layout/nav", NULL, TRUE); ?>