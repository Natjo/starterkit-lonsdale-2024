<?php

get_header();
get_template_part('template-parts/common/header_nav', '');
?>

<main id="main" role="main" tabindex="-1" class="page-simple">
    <?php // get_template_part('template-parts/common/breadcrumb', ''); ?>

    <?php
    $args['title'] = get_the_title();
    get_template_part('template-parts/heros/simple', '', $args);
    ?>

    <section class="contenu-simple">
        <div class="container">
            <div class="rte">
                <?= get_the_content(); ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
