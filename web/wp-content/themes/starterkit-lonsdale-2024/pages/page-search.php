<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 */

get_header();
get_template_part('template-parts/common/header_nav');

?>

<main id="main" role="main" tabindex="-1" >
    <?php get_template_part('template-parts/common/breadcrumb', ''); ?>

    <?php strate::results('news'); ?>
</main>

<?php
get_footer();
