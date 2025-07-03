<?php
/*
Template Name: Page Homepage
*/

get_header();
get_template_part('template-parts/common/header_nav', '');
?>

<main id="main" role="main" class="page-homepage">
    <?php hero::homepage(); ?>

    <?php get_template_part('template-parts/common/strates'); ?>
</main>

<?php
get_footer();
