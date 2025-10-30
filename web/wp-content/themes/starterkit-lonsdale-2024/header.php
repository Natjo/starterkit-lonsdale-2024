<?php
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <?= lsd_seo(); ?>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <link rel="icon" type="image/png" href="<?= THEME_ASSETS ?>favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?= THEME_ASSETS ?>favicon/favicon.svg" />
    <link rel="shortcut icon" href="<?= THEME_ASSETS ?>favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= THEME_ASSETS ?>favicon/apple-touch-icon.png" />
    <link rel="manifest" href="<?= THEME_ASSETS ?>favicon/site.webmanifest" />

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <meta name='HandheldFriendly' content='true' />
    <meta name='format-detection' content='telephone=no' />
    <meta name="msapplication-tap-highlight" content="no">

    <?php wp_head(); ?>

    <?php styles(); ?>

    <link rel="preload" href="<?= THEME_ASSETS ?>fonts/montserrat.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= THEME_ASSETS ?>fonts/aeonik-bold.woff2" as="font" type="font/woff2" crossorigin>
    
    <!-- <link rel="preload" as="image" href="/wp-content/uploads/2022/08/desktop.webp">
    <link rel="preload" as="image" href="/wp-content/uploads/2022/09/desktop1-620x441.webp"> -->

    <!--<link rel="preload" as="script" href="<?= THEME_ASSETS ?>js/app.js" crossorigin>
    <link rel="preload" as="script" href="<?= THEME_ASSETS ?>js/modules/lenis.js" crossorigin> -->
</head>

<body <?php body_class(theme()); ?>>
    <?php get_template_part('template-parts/common/quick_access'); ?>