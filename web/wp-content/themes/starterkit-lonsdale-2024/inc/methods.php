<?php
function is_login_page()
{
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

function checkNonce($nonceContext)
{

    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : 0;
    if (!wp_verify_nonce($nonce, $nonceContext)) {
        exit(__('not authorized', 'domain'));
    }
}

function dateMonthInFr($date)
{
    $english_months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
    $french_months = array('Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc');
    return str_replace($english_months, $french_months,  $date);
}

function youtube_id_from_url($url)
{
    $parts = parse_url($url);

    if (isset($parts['query'])) {
        parse_str($parts['query'], $qs);
        if (isset($qs['v'])) {
            return $qs['v'];
        } else if (isset($qs['vi'])) {
            return $qs['vi'];
        }
    }

    if (isset($parts['path'])) {
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path) - 1];
    }

    return "";
}

// Array of taxonomies terms in post 
function lsd_get_the_terms_name($ID, $taxonomy)
{
    $arr = array();
    $terms = get_the_terms($ID, $taxonomy);
    if ($terms) {
        foreach ($terms as $term) {
            array_push($arr, $term->name);
        }
    }
    return $arr;
}

// Image url fonction id
function lsd_get_thumb($id, $size = 'medium')
{
    if ($id) {
        $img = wp_get_attachment_image_src($id, $size);
        $extension = substr($img[0], strrpos($img[0], '.') + 1);

        if ($extension == 'gif' || $extension == 'GIF') :
            $img = wp_get_attachment_image_src($id, 'full');
        endif;

        $imgUrl = is_array($img) ? reset($img) : "";

        return $imgUrl;
    }
}

// Image url function de mise en avant des articles
function lsd_get_featured($id, $size = 'medium')
{
    if ($id) {
        $img_id = get_post_thumbnail_id($id);
        $img = wp_get_attachment_image_src($img_id, $size);
        $extension = substr($img[0], strrpos($img[0], '.') + 1);

        if ($extension == 'gif' || $extension == 'GIF') :
            $img = wp_get_attachment_image_src($img_id, 'full');
        endif;

        $imgUrl = is_array($img) ? reset($img) : "";

        return $imgUrl;
    }
}



/**
 * isWebp
 * for srcset picture, add ext .webp if not svg 
 */
function isWebp($img)
{
    if (!empty($img)) {
        return pathinfo($img)['extension'] != "svg" ? $img . ".webp" : $img;
    }
}


/**
 * Picture
 */
function picture($image, $class = "", $lazy = false, $breakpoints = [768, 1440])
{
    if (!empty($image)) {
        $sm = $breakpoints[0];
        $wd = $breakpoints[1];
        $lazy = !empty($lazy) ? ' loading="lazy"' : "";
        $class = !empty($class) ? ' class="' . $class . '"' : "";
        $alt = !empty($image["alt"]) ?  $image["alt"]  : "";

        $imgMobile = !empty($image['mobile']) ? $image['mobile'] : null;
        $imgTablet = !empty($image['tablet']) ? $image['tablet'] : null;
        $imgDesktop = $image['desktop'];

        echo '<picture' . $class . '>';
        if (!empty($imgMobile)) {
            echo '<source srcset="' . isWebp($imgMobile) . '" media="(max-width: ' . ($sm - 1) . 'px)" type="image/webp">';
            echo '<source srcset="' . $imgMobile . '" media="(max-width: ' . ($sm - 1) . 'px)" type="image/jpeg">';
        }

        // si mobile et tablet
        if (!empty($imgMobile) && !empty($imgTablet)) {
            echo '<source srcset="' . isWebp($imgDesktop) . '" media="(min-width: ' .  $wd  . 'px)" type="image/webp">';
            echo '<source srcset="' . $imgDesktop . '" media="(min-width: ' .  $wd  . 'px)" type="image/jpeg">';
        }
        // si only mobile
        if (!empty($imgMobile) && empty($imgTablet)) {
            echo '<source srcset="' . isWebp($imgDesktop) . '" media="(min-width: ' . $sm . 'px)" type="image/webp">';
            echo '<source srcset="' . $imgDesktop . '" media="(min-width: ' .  $sm . 'px)" type="image/jpeg">';
        }
        // si only tablet
        if (empty($imgMobile) && !empty($imgTablet)) {
            echo '<source srcset="' . isWebp($imgDesktop) . '" media="(min-width: ' . $wd . 'px)" type="image/webp">';
            echo '<source srcset="' . $imgDesktop . '" media="(min-width: ' . $wd . 'px)" type="image/jpeg">';
        }
        // si only desktop
        if (empty($imgMobile) && empty($imgTablet)) {
            echo '<source srcset="' . isWebp($imgDesktop) . '"  type="image/webp">';
            echo '<source srcset="' . $imgDesktop . '"  type="image/jpeg">';
        }

        //echo '<source srcset="' . isWebp($imgDesktop) . '" media="(min-width: ' . (!empty($imgTablet) ? $wd : $sm) . 'px)" type="image/webp">';
        //echo '<source srcset="' . $imgDesktop . '" media="(min-width: ' . (!empty($imgTablet) ? $wd : $sm)  . 'px)" type="image/jpeg">';

        if (!empty($imgTablet)) {
            echo ' <source srcset="' . isWebp($imgTablet) . '" media="(min-width: ' . $sm . 'px)" type="image/webp">';
            echo ' <source srcset="' . $imgTablet . '" media="(min-width: ' . $sm . 'px)" type="image/jpeg">';
        }

        echo '<img src="' . $imgDesktop . '" alt="' . $alt . '" width="' . $image['width'] . '" height="' . $image['height'] . '"' . $lazy . '>';
        echo '</picture>';
    }
}


/**
 * Create link
 *
 */
function setlink($link, $classes = "")
{
    $target = !empty($link["target"]) && $link["target"] != "" ? 'target="_blank"' : '';
    return '<a href="' . $link["url"] . '" class="' . $classes . '" ' . $target . '>' . $link["title"] . '</a>';
}

/**
 * Create link width picto
 *
 */
function setlinkIcon($link, $classes = "", $icon = "", $width = 13, $height = 17, $label = "")
{
    $target = !empty($link["target"]) ? 'target="_blank"' : '';
    return '<a ' . (!empty($label) ? ' aria-label="' . $label . '"' : "") . ' href="' . $link["url"] . '" class="' . $classes . '" ' . $target . '>' . icon($icon, $width, $height) . "<span>" . $link["title"] . "</span></a>";
}

/**
 * Icon
 */
function icon($name, $width, $height, $url = THEME_ASSETS)
{
    return '<svg class="icon" width="' . $width . '" height="' . $height . '" aria-hidden="true" viewBox="0 0 ' . $width . ' ' . $height . '"><use xlink:href="' . $url . 'img/icons.svg#' . $name . '"></use></svg>';
}
