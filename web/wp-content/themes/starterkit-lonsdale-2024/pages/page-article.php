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
                <?php component::title(2, "Ces articles pourraient vous interresser", "title-3"); ?>

                <ul>
                    <li>
                        <?php
                        card::news(84,[ "sizes" => "400_236", "hx" => 4])
                        ?>
                    </li>

                    <li>
                        <?php card::flexible(462) ?>
                    </li>

                    <li>
                        <?php
                        $args = [
                            "theme" => "theme-color-5",
                            "title" => "popo",
                            "description" => "posdfdsffdgdfpo",
                            "images" => [
                                "desktop" => [
                                    "src" => THEME_ASSETS . "img/test.jpg",
                                    "width" => 800,
                                    "height" => 800
                                ]
                            ]
                        ];

                        card::flexible($args); ?>
                    </li>
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
