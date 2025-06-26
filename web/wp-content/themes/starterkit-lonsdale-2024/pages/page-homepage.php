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

    $cta = get_field('hero-homepage-cta',  $pageID);
    $title = get_field('hero-homepage-title',  $pageID);
    $images = [
        "desktop" => [
            "id" => get_field('hero-homepage-img-desktop', $pageID),
            "size" => "full"
        ],
        "mobile" => [
            "id" => get_field('hero-homepage-img-mobile', $pageID),
            "size" => "full"
        ],
    ];
    $args = [
        'title' => strip_tags($title, '<strong>'),
        'intro' => get_field('hero-homepage-intro', $pageID),
        'cta' => [
            'label' => $cta['title'],
            'link' => $cta['url'],
        ],
        'images' =>  Strate_Helper::images($images),
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
