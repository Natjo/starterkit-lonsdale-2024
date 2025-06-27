<?php
/*
Template Name: Page Homepage
*/

get_header();
get_template_part('template-parts/common/header_nav', '');

$pageID = get_the_ID();
?>

<main id="main" role="main" class="page-homepage">
    <?php

    $fields = get_field('hero-homepage',  $pageID);

    $args = [
        'title' => $fields["title"],
        'intro' => $fields["intro"],
        'link' => $fields["link"],
        'images' => Strate_Helper::images(get_field('block-image',  $pageID)),
    ];

    get_template_part('template-parts/heros/homepage', '', $args);
    ?>


    <div class="container">

        <div>
            <?= component::btn("lorem", "btn btn-1", 'id="popo"') ?>
        </div>

        <br>

        <?php
        $link = [
            "title" => "lorem",
            "url" => "",
            "target" => "_blank",
        ];
        ?>
        <div>
            <?= component::link($link, "link link-1 light", 'id="popo"') ?>
        </div>


        <?= component::icon('linkedin', 36, 36) ?>
    </div>


    <?php get_template_part('template-parts/common/strates', ''); ?>
</main>

<?php
get_footer();
