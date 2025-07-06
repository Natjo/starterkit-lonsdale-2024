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

    public static function search($fields, $classes = null, $attributes = null)
    {
        $args = [
            "label" => $fields["label"],
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/blocks/block', 'search', $args);
    }

    public static function slider($card, $items, $classes = null, $attributes = null)
    {
        $args = [
            "card" => $card,
            "items" => $items,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/blocks/block', 'slider', $args);
    }
}
