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
                "title" => !empty($aStrate["header-title"]) ? $aStrate["header-title"] : "",
                "text" => !empty($aStrate["header-text"]) ? $aStrate["header-text"] : "",
                "link" => !empty($aStrate["header-link"]) ? $aStrate["header-link"] : ""
            ]
        ];

        return $args;
    }
    public static function strates()
    {

        get_template_part('template-parts/common/strates');
    }

    /**
     * 
     */
    public static function wysiwyg($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [
            "column" => $aStrate["column"],
            "text" =>  $aStrate["text"]
        ];

        return array_merge($fields, $options, $header);
    }

    public static function quote($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [
            "quote" => [
                "text" => $aStrate["text"],
                "cite" => $aStrate["cite"]
            ],
        ];

        return array_merge($fields, $options, $header);
    }


    public static function separator($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [];

        return array_merge($fields, $options, $header);
    }

    public static function blocks($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [];

        return array_merge($fields, $options, $header);
    }

    public static function image($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [
            "images" => Helper::images($aStrate["images"]),
        ];

        return array_merge($fields, $options, $header);
    }

    public static function text_image($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);

        $fields = [
            "full" => $aStrate["full"],
            "reverse" => $aStrate["reverse"],
            "images" => Helper::images($aStrate["images"], "620_auto"),
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

        $items["items"] = [];

        foreach ($aStrate["items"] as $id) {
            $card = ["name" => "news"];

            array_push($items["items"], [
                "card" => $card,
                "id" => $id
            ]);
        }

        return array_merge($items, $options, $header);
    }

    public static function accordion($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);

        $header = Strate_Helper::strate_header($aStrate);


        $fields = [
            "column" => $aStrate["column"],
            "items" => $aStrate["items"],
        ];

        return array_merge($fields, $options, $header);
    }
}


class strate
{
    public static function results($post_type,$paged = 1, $query = null)
    {
    
        $cpts = new getCpts($post_type,$paged, $query);

        $fields = [
            "cpts" => $cpts,
        ];

        $args = array_merge($fields);

        get_template_part('template-parts/strates/strate', 'results',$args);
    }
}
