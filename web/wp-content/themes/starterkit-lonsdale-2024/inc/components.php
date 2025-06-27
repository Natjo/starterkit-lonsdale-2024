<?php

class component
{
    public static function picture($images, $lazy = false, $classes = "", $breakpoint = 768)
    {
        if (!empty($images)) {

            $options = [
                "classes" => $classes,
                "lazy" => $lazy,
                "breakpoint" => $breakpoint,
            ];

            get_template_part('template-parts/components/picture', '',  array_merge($images, $options));
        }
    }

    public static function btn($title, $classes = null, $attributes = null)
    {
        $args = [
            "title" => $title,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/components/btn', '', $args);
    }

    public static function link($link, $classes = null, $attributes = null)
    {
        if (empty($link["title"])) return;
        $args = [
            "link" => $link,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/components/link', '', $args);
    }

    public static function icon($name, $width, $height, $url = THEME_ASSETS)
    {
        $args = [
            "name" => $name,
            "width" =>  $width,
            "height" =>  $height,
            "url" =>  $url,
        ];
        get_template_part('template-parts/components/icon', '', $args);
    }

    public static function intro($text, $classes = null, $attributes = null)
    {
        if (empty($text)) return;
        $args = [
            "text" => $text,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/components/intro', '', $args);
    }

    public static function title($title, $classes = null, $attributes = null)
    {
        if (empty($title)) return;
        $args = [
            "title" => $title,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/components/title', '', $args);
    }

    public static function text($text, $classes = null, $attributes = null)
    {
        if (empty($text)) return;
        $args = [
            "text" => $text,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/components/text', '', $args);
    }
}

class block
{
    public static function header($fields, $classes = null, $attributes = null)
    {

        $args = [
            "title" => $fields["title"],
            "text" =>  $fields["text"],
            "cta" =>  $fields["link"],
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/components/header', '', $args);
    }
}
