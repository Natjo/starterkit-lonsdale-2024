<?php

class card
{
    public static function news($values,  $classes = null, $attributes = null)
    {
        if (is_numeric($values)) {
            $post = get_post($values);
            $url = get_permalink($values);
            $description = get_field('card-news-description',  $post->ID);
            $image = get_field('card-news-image',  $post->ID);
            $title = $post->post_title;
            $description = !empty($description) ? $description : "";
            $images = !empty($image) ? Helper::images($image["block-image"], "400_236") : null;
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
