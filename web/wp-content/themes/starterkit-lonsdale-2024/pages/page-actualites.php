<?php
/*
Template Name: Actualites
*/

get_header();
get_template_part('template-parts/common/header_nav', '');

?>

<div id="wrapper">
<main id="main" role="main" tabindex="-1" class="page-actualities">
    <?php get_template_part('template-parts/common/breadcrumb', ''); ?>

    <?php hero::flexible(); ?>

    <?php strate::results('news'); ?>
</main>

<?php
get_footer();?>
</div>
