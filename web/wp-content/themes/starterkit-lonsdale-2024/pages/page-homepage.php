<?php
/*
Template Name: Page Homepage
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');

$pageID =  get_the_ID();

?>

<main id="main" role="main" tabindex="-1" class="page-homepage">
    <?php

    $cta = get_field('hero-homepage-cta',  $pageID);
    $title = get_field('hero-homepage-title',  $pageID);
    $args = [
        'title' => strip_tags($title, '<strong>'),
        'intro' => get_field('hero-homepage-intro', $pageID),
        'cta' => [
            'label' => $cta['title'],
            'link' => $cta['url'],
        ],
        'images' =>  [
            'desktop' => lsd_get_thumb(get_field('hero-homepage-img-desktop', $pageID), 'full'),
            'tablet' => lsd_get_thumb(get_field('hero-homepage-img-tablet', $pageID), 'full'),
            'mobile' => lsd_get_thumb(get_field('hero-homepage-img-mobile', $pageID), 'full'),
            'width' => '1000',
            'height' => '700'
        ]
    ];

    get_template_part('template-parts/heros/hero', 'homepage', $args);
    ?>


    <div class="container">
        <div>
            <?= setbtn("Lorem","btn btn-1"); ?>
        </div>

        <div>
            <?= setbtn("Lorem", "btn btn-1 outline"); ?>
        </div>

        <div>
            <?= setbtn("Lorem", "btn btn-1 light", 'id="dd" data-id="pop" aria-controls="ldkdl"');  ?>
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
            <?= setlink($link, "link link-1 light", 'id="popo"');  ?>
        </div>
        <div>
            <?= setlink($link, "link link-2 light", 'id="popo"');  ?>
        </div>
       
    </div>


    <?php get_template_part('template-parts/general/block', 'strates'); ?>
</main>

<?php
get_footer();
