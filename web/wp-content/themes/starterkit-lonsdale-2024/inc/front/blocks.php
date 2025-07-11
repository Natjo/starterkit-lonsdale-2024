<?php

class block
{
    public static function header($fields, $classes = null, $attributes = null)
    {
        if (empty($fields["title"]) && empty($fields["text"]) && empty($fields["link"])) return;

        $args = [
            "title" => $fields["title"],
            "text" =>  $fields["text"],
            "cta" =>  $fields["link"],
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/blocks/block', 'header', $args);
    }

    public static function sidebar($classes = null, $attributes = null)
    {
        $name = "sidebar-news";
        $pageID = get_the_ID();

        // console($field);
        // if (empty($fields["title"]) && empty($fields["text"]) && empty($fields["link"])) return;
        //console('fdsdsd');
        $args = [
            //  "title" => $fields["title"],
            "blocks" =>  get_field($name . "-blocks", $pageID)["blocks"],
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/blocks/block', 'sidebar', $args);
    }

    public static function search($fields, $classes = null, $attributes = null)
    {
        $args = [
            "label" => $fields["label"],
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/blocks/block', 'search', $args);
    }

    public static function slider($items, $classes = null, $attributes = null)
    {
        $args = [
            "items" => $items,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/blocks/block', 'slider', $args);
    }
}
