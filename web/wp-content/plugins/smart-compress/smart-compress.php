<?php
/*
Plugin Name: Smart Compress
Description: Generate webp on upload or on the fly
Version: 1.0.5
Author: Martin Jonathan
*/

global $sc_compression;
global $sc_radius;
global $sc_sigma;
global $sc_blur;
global $sc_quality;
global $sc_type;
global $sc_table;
global $sc_table_prefix;
global $hasimagick;
global $sc_quality_table;


/**
 * 
 * Generate img webp using Imagick module
 * $originalFilepath => path of original image
 * $resizedFilepath => path of webp image, must be in the same folder as original
 * $newWidth & $newHeight => reals sizes from wp_get_attachment_metadata($img_id)
 * $crop => crop position defined in add_image_size
 * 
 */
function sm_generate($originalFilepath, $resizedFilepath, $newWidth, $newHeight, $crop)
{
    global $sc_compression;
    global $sc_radius;
    global $sc_sigma;
    global $sc_blur;
    global $sc_quality;

    if ($sc_quality == 0) { //optimal
        $sc_compression = 70;
        $sc_blur = 1;
        $sc_radius = .8;
        $sc_sigma = .6;
    }
    if ($sc_quality == 1) { //best
        $sc_compression = 80;
        $sc_blur = 0.96;
        $sc_radius = 1;
        $sc_sigma = .8;
    }

    $image = new Imagick($originalFilepath);
    $format = $image->getImageFormat();

    $w = $image->getImageWidth();
    $h = $image->getImageHeight();
    $ratio = $w / $h;

    $ratio_new = $newWidth / $newHeight;

    if ($ratio_new < $ratio) {
        $resize_w = $newHeight * $ratio;
        $resize_h = $newHeight;
    } else {
        $resize_w = $newWidth;
        $resize_h = $newWidth / $ratio;
    }

    $image->setImageCompressionQuality($sc_compression);

    $image->stripImage();
    $image->setOption('filter:support', '2.0');
    if ($format == "GIF") {
        $image = $image->coalesceImages();

        do {
            $image->resizeImage(round($resize_w), round($resize_h), Imagick::FILTER_LANCZOS, $sc_blur, false);
            $image->cropImage(round($newWidth), round($newHeight), round(($resize_w - $newWidth) / 2), round(($resize_h - $newHeight) / 2));
        } while ($image->nextImage());

        $image = $image->deconstructImages();
    } else {
        $image->resizeImage(round($resize_w), round($resize_h), Imagick::FILTER_LANCZOS, $sc_blur, false);

        if ($crop == ["center", "top"]) {
            $image->cropImage($newWidth, $newHeight, ($resize_w - $newWidth) / 2, 0);
        } elseif ($crop == ["center", "bottom"]) {
            $image->cropImage($newWidth, $newHeight, ($resize_w - $newWidth) / 2, $resize_h - $newHeight);
        } else {
            $image->cropImage(round($newWidth), round($newHeight), round(($resize_w - $newWidth) / 2), round(($resize_h - $newHeight) / 2));
        }
    }
    //$image->setOption('webp:lossless', 'true');
    $image->sharpenImage($sc_radius, $sc_sigma);
    $image->setImageFormat("webp");
    $image->setImageAlphaChannel(Imagick::ALPHACHANNEL_ACTIVATE);
    $image->setBackgroundColor(new ImagickPixel('transparent'));
    if ($format == "GIF") {
        $image->writeImages($resizedFilepath, true);
    } else {
        $image->writeImage($resizedFilepath);
    }
}

/**
 * 
 * If type 1 (on the fly), generate webp if not exist
 * need to add this script in the picture/image component or template, 
 * width the id of the image and the size_name (from add_image_size) of the image to display
 * 
 * if (function_exists('sm_onthefly')) {
 *    sm_onthefly($id, $size_name);
 * }
 * 
 */
function sm_onthefly($id, $size_name)
{
    global $sc_type;

    if ($sc_type == 1) {
        $metadata = wp_get_attachment_metadata($id);
        global $_wp_additional_image_sizes;
        $originalFilepath = WP_CONTENT_DIR . "/uploads/" . $metadata["file"];
        $dirname = pathinfo($metadata['file'], PATHINFO_DIRNAME);
        $crop = $_wp_additional_image_sizes[$size_name]["crop"];

        if (!empty($metadata['sizes'][$size_name])) {
            $size = $metadata['sizes'][$size_name];
            $newWidth = $size["width"];
            $newHeight = $size["height"];
            $filename = pathinfo($size["file"], PATHINFO_FILENAME);
        } else {
            $newWidth = $metadata["width"];
            $newHeight = $metadata["height"];
            $filename = pathinfo($metadata["file"], PATHINFO_FILENAME);
        }

        $resizedFilepath = WP_CONTENT_DIR . "/uploads/" . $dirname . "/" .  $filename . ".webp";
        sm_generate($originalFilepath, $resizedFilepath, $newWidth, $newHeight, $crop);
    }
}


/**
 * Change all wysiwyg image type by webp
 * 
 * 
 */
function sm_wysiwygWebp($content)
{

    return preg_replace_callback('/<img[^>]+>/i', function ($matches) {
        $img = $matches[0];
        preg_match('/src=["\']([^"\']+)["\']/', $img, $srcMatch);
        $src = $srcMatch[1] ?? '';
        $webp = preg_replace('/(.jpg|.jpeg|.png)/i', '.webp', $src);
        if (file_exists(str_replace("https://" . $_SERVER['HTTP_HOST'] . "/", ABSPATH,  $webp))) {
            //echo "exist";
            return preg_replace('/.(jpg|jpeg|png)/i', '.webp', $img);
        } else {
            global $sc_type;
            if ($sc_type == 1) {
                preg_match('/wp-image-([0-9]+)/', $img, $idMatch);
                $id = $idMatch[1] ?? '';
                $metadata = wp_get_attachment_metadata($id);
                if (!empty($metadata['file'])) {
                    $file = $metadata['file'];
                    $newWidth = $metadata['width'];
                    $newHeight =  $metadata['height'];
                    $dirname = pathinfo($file, PATHINFO_DIRNAME);
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $originalFilepath = WP_CONTENT_DIR . "/uploads/" .  $file;
                    $resizedFilepath = WP_CONTENT_DIR . "/uploads/" . $dirname . "/" .  $filename . ".webp";
                    sm_generate($originalFilepath, $resizedFilepath, $newWidth, $newHeight, null);
                } else {
                    /*  
                    // if meta dont have file
                    // not tha regenerate restore this pb
                    $file = get_attached_file($id);
                    $info = getimagesize($file);
                    $meta = array (
                        'width' => $info[0],
                        'height' => $info[1],
                        'file' => basename($file),
                        'sizes' => $metadata['sizes']
                        'image_meta' => $metadata['image_meta']
                    );
                    update_post_meta($id, '_wp_attachment_metadata', $meta);*/
                }
            }

            return $img;
        }
    }, $content);
}



/**
 * 
 * Smart compress core
 * Using Imagick, if not installed the pluggin will be disabled
 * 
 * adding table {{prefixe}}_smart_compress
 * 
 * type => 0 (on upload) or 1 (on the fly)
 * quality => 0 to 100
 * 
 * hook used:
 *  - wp_generate_attachment_metadata: 
 *      called on uplaod imgage from médias or image field.
 *      Used if type 0 (on upload) is selected
 * 
 *  - wp_delete_file:
 *      called when image is deleted 
 *      Used for all type
 * 
 */
function smart_compress()
{

    if (!is_user_logged_in()) return;
    global $sc_quality_table;
    global $wpdb;
    global $sc_type;
    global $sc_table;
    global $sc_quality;
    global $sc_last_generate;
    global $sc_table_prefix;
    global $hasimagick;

    $hasimagick = extension_loaded('imagick') ? true : false;

    // create smart_compress table
    $charset_collate = $wpdb->get_charset_collate();
    $sc_table = $sc_table_prefix . "smart_compress";
    if (!$wpdb->get_var("SHOW TABLES LIKE '$sc_table'") == $sc_table) {
        $sql = "CREATE TABLE $sc_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        option tinytext NOT NULL,
        value blob NOT NULL,
        PRIMARY KEY  (id)
      ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // create type table
    $sc_type_table = $wpdb->get_results("SELECT * FROM " . $sc_table . " WHERE option = 'type'");
    if (empty($sc_type_table)) {
        $data = array('option' => "type", 'value' => 1);
        $format = array('%s', '%d');
        $wpdb->insert($sc_table, $data, $format);
        $sc_type = 0;
    } else {
        $sc_type = $sc_type_table[0]->value;
    }

    // create quality table
    $sc_quality_table = $wpdb->get_results("SELECT * FROM " . $sc_table . " WHERE option = 'quality'");
    if (empty($sc_quality_table)) {
        $data = array('option' => "quality", 'value' => 0);
        $format = array('%s', '%d');
        $wpdb->insert($sc_table, $data, $format);
        $sc_quality = 0;
    } else {
        $sc_quality = $sc_quality_table[0]->value;
    }

    // create last_generate table
    $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
    );

    $attachments = get_posts($args);

    $arr = [];

    if ($attachments) {
        foreach ($attachments as $i => $post) {
            $mime = $post->post_mime_type;
            if ($mime == "image/jpeg" || $mime == "image/png") {
                if (preg_match('/uploads\/([0-9]{4})\//',  $post->guid, $matches)) {
                    array_push($arr,  $matches[1]);
                }
            }
        }
    }

    $arr = array_unique($arr);

    rsort($arr);

    $years = [];

    foreach ($arr as $year) {
        $years[$year]["active"] =  1;
        $years[$year]["date"] =  "";
    }

    $sc_last_generate_table = $wpdb->get_results("SELECT * FROM " . $sc_table . " WHERE option = 'last_generate'");
    if (empty($sc_last_generate_table)) {
        $data = array('option' => "last_generate", 'value' => json_encode($years));
        $format = array('%s', '%s');
        $wpdb->insert($sc_table, $data, $format);
        $sc_last_generate = "";
    } else {
        $sc_last_generate = json_decode($sc_last_generate_table[0]->value, true);
    }

    if ($hasimagick) {

        // upload on medias/post
        add_filter('wp_generate_attachment_metadata', 'replace_uploaded_image', 20, 2);
        function replace_uploaded_image($image_data, $id)
        {
            global $sc_type;

            if ($sc_type == 0) {
                $metadata = wp_get_attachment_metadata($id);
                $originalFilepath = WP_CONTENT_DIR . "/uploads/" . $metadata["file"];
                $mime = mime_content_type($originalFilepath);

                if ($mime == "image/jpeg"  || $mime == "image/png") {
                    global $_wp_additional_image_sizes;

                    $dirname = pathinfo($metadata['file'], PATHINFO_DIRNAME);

                    $metadata["sizes"]['original'] = [
                        "file" => $image_data["file"],
                        "width" => $image_data["width"],
                        "height" => $image_data["height"],
                    ];

                    foreach ($metadata["sizes"] as $key => $size) {
                        $crop = $key == "original" ? [] : $_wp_additional_image_sizes[$key]["crop"];

                        $filename = pathinfo($size["file"], PATHINFO_FILENAME);
                        $resizedFilepath = WP_CONTENT_DIR . "/uploads/" . $dirname . "/" .  $filename . ".webp";

                        sm_generate($originalFilepath, $resizedFilepath,  $size["width"],  $size["height"], $crop);
                    }
                }
            }
            return $image_data;
        }

        // when image deleted
        add_filter('wp_delete_file', 'delete_attachment');
        function delete_attachment($image_data)
        {
            return preg_replace_callback('/^(.+\.)(?:bmp|gif|jpe?g|pdf|png)$/i', function (array $matches): string {
                wp_delete_file($matches[1] . ".webp");
                return $matches[0];
            }, $image_data);
        }
    }

    // Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
    require_once plugin_dir_path(__FILE__) . 'includes/sc-functions.php';
    require_once plugin_dir_path(__FILE__) . 'includes/sc-admin-ajax.php';
}

add_action('init', 'smart_compress');
