<?php
class Helper
{
    public static function images($arr, $size_desktop = "full", $size_mobile = "full")
    {
        if (empty($arr)) return;
        $images = [
            "desktop" => [
                "id" => $arr["image-desktop"],
                "size" => $size_desktop
            ]

        ];

        if (!empty($arr["image-mobile"])) {
            $images["mobile"] = [
                "id" => $arr["image-mobile"],
                "size" =>  $size_mobile
            ];
        }

        $args = [];

        foreach ($images as $key => $item) {
            if (!empty($item["id"])) {
                $image = lsd_get_thumb($item["id"], $item["size"]);
                $ext = pathinfo($image[0])['extension'];
                if ($ext != "svg") {
                    $webp = str_replace("." . $ext, ".webp", $image[0]);
                    if (file_exists(str_replace("https://" . $_SERVER['HTTP_HOST'] . "/", ABSPATH, $webp))) {
                        $args[$key]["webp"] = $webp;
                    }
                }
                $args[$key]["src"] = $image[0];
                $args[$key]["width"] = $image[1];
                $args[$key]["height"] = $image[2];
                $args[$key]["alt"] = $image[3];
            }
        }

        return  $args;
    }
}

class Strate_Helper
{
    public static function strate_options($aStrate)
    {
        $args = [
            "options" => [
                "margin" => !empty($aStrate['strates-options-margin']) ? $aStrate['strates-options-margin'] : "",
                "container" => !empty($aStrate['strates-options-container']) ? $aStrate['strates-options-container'] : "",
                "padding" => !empty($aStrate['strates-options-padding']) ? $aStrate['strates-options-padding'] : "",
                "background" => !empty($aStrate['strates-options-background']) ? $aStrate['strates-options-background'] : "",
                "id" => !empty($aStrate['strates-options-id']) ? $aStrate['strates-options-id'] : "",
            ]
        ];

        return $args;
    }

    public static function strate_header($aStrate)
    {
        $args = [
            "header" => [
                "title" => !empty($aStrate["block-header-title"]) ? $aStrate["block-header-title"] : "",
                "text" => !empty($aStrate["block-header-text"]) ? $aStrate["block-header-text"] : "",
                "link" => !empty($aStrate["block-header-link"]) ? $aStrate["block-header-link"] : ""
            ]
        ];

        return $args;
    }

    /**
     * 
     */
    public static function wysiwyg($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [
            "text" =>  $aStrate["text"]
        ];

        return array_merge($fields, $options, $header);
    }

    public static function image($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [
            "images" => Helper::images($aStrate["block-image"]),
        ];

        return array_merge($fields, $options, $header);
    }

    public static function text_image($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [
            "images" => Helper::images($aStrate["block-image"], "620_auto"),
            "title" =>  $aStrate["title"],
            "text" =>  $aStrate["text"],
            "link" =>  $aStrate["link"]
        ];

        return array_merge($fields, $options, $header);
    }

    public static function news($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [
            "items" =>  $aStrate["items"],
            "link" =>  $aStrate["link"],
        ];

        return array_merge($fields, $options, $header);
    }

    public static function slider($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [
            "card" =>  "news",
            "items" => $aStrate["items"]
        ];

        return array_merge($fields, $options, $header);
    }
}

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
            'date' => get_the_date('d.m.Y'),
            'images' => Helper::images(get_field('hero-news-image',  $pageID)['block-image']),
        ];

        get_template_part('template-parts/heros/hero', 'article', $args);
    }
}
