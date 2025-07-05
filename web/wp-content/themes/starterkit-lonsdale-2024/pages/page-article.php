<?php

get_header();
get_template_part('template-parts/common/header_nav');
?>

<main id="main" role="main" tabindex="-1" class="page-article">
    <?php get_template_part('template-parts/common/breadcrumb'); ?>

    <article>
        <?php hero::article(); ?>

        <div class="layout-sidebar">
            <div class="sidebar">
                <?php component::title("Ces articles pourraient vous interresser","title-2"); ?>  

                <ul>
                    <li><?php card::news(84); ?>  </li>
                </ul>
            </div>

            <div class="content">
                <?php get_template_part('template-parts/common/strates'); ?>
            </div>
        </div>
    </article>
</main>

<?php
get_footer();
