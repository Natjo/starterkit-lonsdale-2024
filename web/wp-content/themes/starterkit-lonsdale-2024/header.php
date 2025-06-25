<?php
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <?= lsd_seo(); ?>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <link rel="icon" type="image/png" href="<?= THEME_ASSETS ?>favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?= THEME_ASSETS ?>favicon.svg" />
    <link rel="shortcut icon" href="<?= THEME_ASSETS ?>favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= THEME_ASSETS ?>apple-touch-icon.png" />
    <link rel="manifest" href="<?= THEME_ASSETS ?>site.webmanifest" />

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <meta name='HandheldFriendly' content='true' />
    <meta name='format-detection' content='telephone=no' />
    <meta name="msapplication-tap-highlight" content="no">

    <?php wp_head(); ?>

    <link rel='stylesheet' href='<?= THEME; ?>assets/styles.css?v=<?= VERSION; ?>' />
</head>

<body <?php body_class(); ?>>