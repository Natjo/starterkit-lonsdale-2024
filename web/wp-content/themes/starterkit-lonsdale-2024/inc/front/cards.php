<?php

class card
{
    private static function base($card, $post, $args)
    {
        $size = $args["sizes"];
        $classes = !empty($args["classes"]) ? $args["classes"] : "";
        $attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
        $hx = !empty($args["hx"]) ? $args["hx"] : 3;

        if (is_numeric($post)) {
            $post = get_post($post);
            $theme = get_field('page-theme',  $post->ID);
            $url = get_permalink($post);
            $description = get_field($card . '-description',  $post->ID);
            $image = get_field($card . '-images',  $post->ID);
            $title = !empty($post->post_title) ? $post->post_title :  "";
            if (get_field($card . '-title',  $post->ID)) {
                $title = get_field($card . '-title',  $post->ID);
            }
            $description = !empty($description) ? $description : "";
            $images = !empty($image) ? Helper::images($image, $size) : null;
        } else {
            $title = !empty($post["title"]) ? $post["title"] : "";
            $description = !empty($post["description"]) ? $post["description"] : "";
            $images = !empty($post["images"]) ? $post["images"] : "";
            $url = !empty($post["url"]) ? $post["url"] : "";
            $theme = !empty($post["theme"]) ? $post["theme"] : "";
        }

        $args1 = [
            "theme" => $theme,
            "hx" => $hx,
            "title" => $title,
            "description" => $description,
            "images" => $images,
            "url" => $url,
            "classes" => $classes,
            "attributes" => $attributes
        ];

        get_template_part('template-parts/cards/' . $card, 'nws',  $args1);
    }

    public static function news($post, $args = [])
    {
        $sizes = "400_236";

        card::base("card-news", $post, array_merge(["sizes" => $sizes], $args));
    }

    public static function flexible($post, $args = [])
    {
        $sizes = "400_236";

        card::base("card-flexible", $post, array_merge(["sizes" => $sizes], $args));
    }
}
