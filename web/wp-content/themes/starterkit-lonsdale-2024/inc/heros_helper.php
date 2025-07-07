<?php

class hero
{
    public static function homepage()
    {
        $pageID = get_the_ID();

        $args = [
            'title' => get_field('hero-homepage-title',  $pageID),
            'intro' => get_field('hero-homepage-intro',  $pageID),
            'link' => get_field('hero-homepage-link',  $pageID),
            'images' => Helper::images(get_field('hero-homepage-image',  $pageID)['block-image']),
        ];

        get_template_part('template-parts/heros/hero', 'homepage', $args);
    }
    public static function flexible()
    {
        $pageID = get_the_ID();

        $args = [
            'title' => get_field('hero-flexible-title',  $pageID),
            'intro' => get_field('hero-flexible-intro',  $pageID),
            'images' => Helper::images(get_field('hero-flexible-image',  $pageID)['block-image']),
        ];

        get_template_part('template-parts/heros/hero', 'flexible', $args);
    }
    public static function article()
    {
        $pageID = get_the_ID();

        $args = [
            'title' => get_field('hero-news-title',  $pageID),
            'intro' => get_field('hero-news-intro',  $pageID),
            'date' => [
                "value" => get_the_date('d.m.Y'),
                "datetime" => get_the_date('Y-m-d'),
            ],
            'images' => Helper::images(get_field('hero-news-image',  $pageID)['block-image']),
        ];

        get_template_part('template-parts/heros/hero', 'article', $args);
    }
}
