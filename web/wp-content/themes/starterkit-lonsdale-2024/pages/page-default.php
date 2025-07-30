<?php
/*
Template Name: Plan du site
*/

get_header();
get_template_part('template-parts/common/header_nav');
?>

<main id="main" role="main" tabindex="-1">
    <?php get_template_part('template-parts/common/breadcrumb', ''); ?>

    <?php hero::page(); ?>

     <div class="layout-flex">
        <?php Strate_Helper::strates();?>
    </div>
</main>

<?php
get_footer();
