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
class card
{
    public static function news($values,  $classes = null, $attributes = null)
    {
        if (is_numeric($values)) {
            $post = get_post($values);
            $url = get_permalink($values);
            $field = get_field("card-news", $values);
            $title = $post->post_title;
            $description = !empty($field["description"]) ? $field["description"] : "";
            $images = !empty($field["block-image"]) ? Helper::images($field["block-image"], "400_236") : null;
        } else {
            $title = $values["title"];
            $description = !empty($values["description"]) ? $values["description"] : "";
            $images = !empty($values["images"]) ? $values["images"] : "";
            $url = !empty($values["url"]) ? $values["url"] : "";
        }

        $args = [
            "title" =>  $title,
            "description" => $description,
            "images" => $images,
            "url" => $url,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/cards/card', 'news',  $args);
    }
}

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
