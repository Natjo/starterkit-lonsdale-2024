<?php
/*
Template Name: Page flexible
*/

get_header();
get_template_part('template-parts/common/header_nav');
?>

<main id="main" role="main" tabindex="-1" class="page-flexible">
    <?php get_template_part('template-parts/common/breadcrumb', ''); ?>

    <?php hero::flexible(); ?>

    <div class="layout-flex">
        <?php get_template_part('template-parts/common/strates'); ?>
    </div>
</main>

<?php
get_footer();
