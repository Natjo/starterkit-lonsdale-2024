<?php

class Strate_Helper
{
    public static function images($arr)
    {
        $args = [];

        foreach ($arr as $key => $item) {
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

    public static function strate_options($aStrate)
    {
        $args = [
            "options" => [
                "margin" => !empty($aStrate['strates-options-margin']) ? $aStrate['strates-options-margin'] : "",
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

        $images = [
            "desktop" => [
                "id" => $aStrate["block-image"]["image-desktop"],
                "size" => "full"
            ],
            "mobile" => [
                "id" => $aStrate["block-image"]["image-mobile"],
                "size" => "full"
            ],
        ];

        $fields = [
            "images" => Strate_Helper::images($images),
        ];

        return array_merge($fields, $options, $header);
    }
}
