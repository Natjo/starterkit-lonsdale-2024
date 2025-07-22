<?php
/*
Template Name: Plan du site
*/

get_header();
get_template_part('template-parts/common/header_nav');
?>

<main id="main" role="main" tabindex="-1" class="page-sitemap">
    <?php get_template_part('template-parts/common/breadcrumb', ''); ?>

    <?php hero::page(); ?>

    <section class="strate strate-sitemap">
        <ul>
            <?php wp_list_pages(array(
                'title_li' => '',
            ));
            ?>
        </ul>
    </section>
</main>

<?php
get_footer();
