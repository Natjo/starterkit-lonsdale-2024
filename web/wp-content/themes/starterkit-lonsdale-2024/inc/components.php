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
}
