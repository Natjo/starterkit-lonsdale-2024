<?php

class Strate_Helper
{

    public static function strate_options($aStrate)
    {
        $args = [
            "options" => [
                "margin" => !empty($aStrate['strates-options']["margin"]) ? $aStrate['strates-options']["margin"] : ""
            ]
        ];

        return $args;
    }

    public static function strate_wysiwyg($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);
        $block_text = Block_Helper::block_text($aStrate);

        return array_merge($block_text, $options);
    }

    public static function strate_image($aStrate)
    {
        $options = Strate_Helper::strate_options($aStrate);
        $block_image = Block_Helper::block_image($aStrate, array(
            "desktop" => "full",
            "mobile" => "full"
        ));

        return array_merge($block_image, $options);
    }
}
