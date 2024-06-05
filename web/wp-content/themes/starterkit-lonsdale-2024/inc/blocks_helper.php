<?php

class Block_Helper
{

    public static function block_text($aStrate)
    {
        $args = array(
            "block-text" => array(
                "text" =>  $aStrate["block-text"]["text"],
            ),
        );

        return  $args;
    }

    public static function block_image($aStrate,$sizes = array())
    {
        $block = $aStrate['block-image'];
        $image_desktop = "";
        $image_mobile = "";
        $image_tablet = "";

        if (!empty($block['image-desktop'])) {
            if (empty($sizes["desktop"]) || $sizes["desktop"] == "full") {
                $image_desktop = $block['image-desktop']['url'];
                $width = $block['image-desktop']['width'];
                $height = $block['image-desktop']['height'];
            } else {
                $image_desktop = $block['image-desktop']['sizes']["415_300"];
                $width = $block['image-desktop']['sizes']["415_300-width"];
                $height = $block['image-desktop']['sizes']["415_300-height"];
            }
        }

        if (!empty($block['image-mobile'])) {
            if (empty($sizes["mobile"]) || $sizes["mobile"] == "full") {
                $image_mobile = $block['image-mobile']['url'];
            } else {
                $image_mobile = $block['image-mobile']['sizes']["415_300"];
            }
        }


        if (!empty($block['image-tablet'])) {
            if (empty($sizes["tablet"]) || $sizes["tablet"] == "full") {
                $image_tablet = $block['image-tablet']['url'];
            } else {
                $image_tablet = $block['image-tablet']['sizes']["415_300"];
            }
        }


        $args = array(
            "block-image" =>  array(
                'images' => array(
                    'desktop' => $image_desktop,
                    'mobile' => $image_mobile,
                    'tablet' => $image_tablet,
                    'width' => $width,
                    'height' => $height
                )
            )
        );
        return  $args;
    }
}
