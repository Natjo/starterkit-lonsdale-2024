<?php

/**
 * Picture
 * create sources from $args["images]
 * 
 */

function source($img, $width, $height, $id, $size_name, $maxwidth = null, $minwidth = null)
{
    $media = (!empty($maxwidth) || !empty($minwidth)) ? ' media="' : "";
    if (!empty($maxwidth)) {
        $media .= '(max-width: ' . ($maxwidth - 1) . 'px)"';
    } elseif (!empty($minwidth)) {
        $media .= '(min-width: ' . ($minwidth) . 'px)"';
    }

    $ext = pathinfo($img)['extension'];

    if ($ext != "svg") {
        $webp = str_replace("." . $ext, ".webp", $img);

        if (file_exists(str_replace("https://" . $_SERVER['HTTP_HOST'] . "/", ABSPATH, $webp))) {
            echo '<source srcset="' . $webp . '" ' . $media . ' type="image/webp">';
        } else {
            /**
             * smart_compress
             * generate webp on the fly
             * $id => image id
             * $size_name => image add_image_size->name
             * 
             */
            /* if (function_exists('sm_onthefly') && !empty($id) && !empty($size_name)) {
                sm_onthefly($id, $size_name);
            }*/
        }
    }

    echo '<source width="' . $width . '" height="' . $height . '" srcset="' . $img . '" ' . $media . ' type="image/' . $ext . '">';
}

function picture($image, $class = "", $lazy = false, $breakpoints = [768, 1920])
{
    if (!empty($image)) {
        $imgMobile = !empty($image['mobile']) ? $image['mobile'] : null;
        $imgTablet = !empty($image['tablet']) ? $image['tablet'] : null;
        $imgDesktop = $image['desktop'];

        $id = !empty($image["id"]) ? $image["id"] : null;
        $size_name = !empty($image["size"]) ?  $image["size"] : null;
        $sm = $breakpoints[0];
        $wd = $breakpoints[1];
        $lazy = !empty($lazy) ? ' loading="lazy"' : "";
        $class = !empty($class) ? ' class="' . $class . '"' : "";
        $alt = !empty($image["alt"]) ?  'alt="' . $image["alt"] . '"'  : 'alt=""';

        $width = $image['width'];
        $height = $image['height'];

        // Attention ordre des sources important
        echo '<picture' . $class . '>';
        if (!empty($imgMobile)) {
            $width1 = !empty($image['mobile_width']) ? $image['mobile_width'] : $image['width'];
            $height1 = !empty($image['mobile_height']) ? $image['mobile_height'] : $image['height'];
            source($imgMobile, $width1, $height1, $id, $size_name, $sm, null);
        }

        // si mobile et tablet alors l'image desktop après sm
        if (!empty($imgMobile) && !empty($imgTablet)) {
            source($imgDesktop, $width, $height, $id, $size_name, null, $wd);
        }

        // si mobile alors desktop après sm
        if (!empty($imgMobile) && empty($imgTablet)) {
            source($imgDesktop, $width, $height, $id, $size_name, null, $sm);
        }

        // si tablet alors desktop après wd
        if (empty($imgMobile) && !empty($imgTablet)) {
            source($imgDesktop, $width, $height, $id, $size_name, null, $wd);
        }

        // si only desktop
        if (empty($imgMobile) && empty($imgTablet)) {
            source($imgDesktop, $width, $height, $id, $size_name, null, null);
        }

        if (!empty($imgTablet)) {
            source($imgTablet, $width, $height, $id, $size_name, null, $sm);
        }

        echo '<img src="' . $imgDesktop . '" ' . $alt . ' width="' . $image['width'] . '" height="' . $image['height'] . '"' . $lazy . '>';
        echo '</picture>';
    }
}


/**
 * Icon
 */
function icon($name, $width, $height, $url = THEME_ASSETS)
{
    return '<svg class="icon" width="' . $width . '" height="' . $height . '" aria-hidden="true" viewBox="0 0 ' . $width . ' ' . $height . '"><use xlink:href="' . $url . 'img/icons.svg#' . $name . '"></use></svg>';
}


/**
 * Btns
 */
function setbtn($name, $classes = null, $attr = null)
{
    return '<button class="' . (!empty($classes) ? ' ' . $classes : '') . '" ' . (!empty($attr) ? ' ' . $attr : '') . '>' . $name . '</button>';
}

/**
 * Links
 */
function setlink($link, $options = null, $attr = null)
{
    $target = !empty($link["target"]) && $link["target"] != "" ? 'target="_blank"' : '';
    $attributes = (!empty($attr) ? ' ' . $attr : '');
    $classes =  (!empty($options) ? ' ' . $options : '');
    return '<a href="' . $link["url"] . '" class="' . $classes  . '" ' . $target . $attributes . '>' . $link["title"] . '</a>';
}
