<?php

function theme_setup()
{
    load_theme_textdomain('theme', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ),
        'post-thumbnails'
    );

    register_nav_menus(array(
        'menu-footer' => 'Menu Footer',
        'menu-header' => 'Menu Header',
        'sitemap' => 'Sitemap'
    ));

    show_admin_bar(false);

    add_image_size('415_300', 415, 300, array('center', 'center'));

    add_image_size('400_236', 400, 236, array('center', 'center'));
    add_image_size('620_auto', 620, 0, array('center', 'center'));
    
}


//REMOVE FILE TYPE
add_filter('style_loader_tag', 'remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'remove_type_attr', 10, 2);

function remove_type_attr($tag, $handle)
{
    return preg_replace("/type=['\"]text\/(javascript|css)['\"]/", '', $tag);
}

function remove_menus()
{
    remove_menu_page('edit.php'); // remove articles from menu
    remove_menu_page('edit-comments.php'); //Comments
}
add_action('admin_menu', 'remove_menus');

function acf_add_main_options()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page('Paramètres');
    }
}

add_filter('wp_default_scripts', 'removeJqueryMigrate');
function removeJqueryMigrate(&$scripts)
{
    if (!is_admin()) {
        $scripts->remove('jquery');
        $scripts->add('jquery', false, array('jquery-core'), '1.4.1');
    }
}

add_action('after_setup_theme', 'theme_setup');
// add_action( 'admin_bar_menu', 'remove_default_post_type_menu_bar', 999 );
// add_action( 'wp_dashboard_setup', 'remove_draft_widget', 999 );
add_action('wp_loaded', 'acf_add_main_options');

//REMOVE : emoji 🗑
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Remove wp-embed.min.js
function my_deregister_scripts()
{
    wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'my_deregister_scripts');

// Remove default css
function smartwp_remove_wp_block_library_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // Remove WooCommerce block CSS
}
add_action('wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100);


// Remove unused format
function disable_unused_format($sizes)
{
    unset($sizes['300x300']);
    unset($sizes['600x800']);
    /* unset($sizes['768x768']);*/
    unset($sizes['large']);
    unset($sizes['2048x2048']);
    unset($sizes['scaled']);
    unset($sizes['1536x1536']);
    unset($sizes['thumbnail_example']);
    unset($sizes['medium_large']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_unused_format');


// no image compression
add_filter('jpeg_quality', function ($arg) {
    return -1;
});
add_filter('wp_editor_set_quality', function ($arg) {
    return -1;
});

// empeche que l'image soit scaled si trop grande
add_filter('big_image_size_threshold', '__return_false');

/*  DISABLE GUTENBERG STYLE IN HEADER| WordPress 5.9 */
function wps_deregister_styles()
{
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'wps_deregister_styles', 100);


// --------------------------------------------------------------------------------------
// Désactivation de l'éditeur wordpress sur les page (pour n'avoir que les champs ACF)
// --------------------------------------------------------------------------------------
add_action('init', 'init_remove_support', 100);

function init_remove_support()
{
    $template_file = "";

    // If not in the admin, return.
    if (!is_admin()) {
        return;
    }
    // Get the post ID on edit post with filter_input super global inspection.
    $current_post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
    // Get the post ID on update post with filter_input super global inspection.
    $update_post_id = filter_input(INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT);
    // Check to see if the post ID is set, else return.
    if (isset($current_post_id)) {
        $post_id = absint($current_post_id);
    } else {
        if (isset($update_post_id)) {
            $post_id = absint($update_post_id);
        } else {
            return;
        }
    }
    // Don't do anything unless there is a post_id.
    if (isset($post_id)) {
        $template_file = get_post_meta($post_id, '_wp_page_template', true);
    }
    if ('default' !== $template_file) {
        remove_post_type_support("page", 'editor');
    }
}





/*
 * TINY MCE
 */

// tiny mce Formatage avec les <p>
add_filter('tiny_mce_before_init', 'prevent_deleting_pTags');
function prevent_deleting_pTags($init)
{
    $init['wpautop'] = false;
    return $init;
}

// Tiny MCE Custom Formats
add_filter('mce_buttons_2', 'juiz_mce_buttons_2');
if (!function_exists('juiz_mce_buttons_2')) {
    function juiz_mce_buttons_2($buttons)
    {
        array_unshift($buttons, 'styleselect');

        return $buttons;
    }
}
add_filter('tiny_mce_before_init', 'juiz_mce_before_init');
if (!function_exists('juiz_mce_before_init')) {
    function juiz_mce_before_init($styles)
    {
        $style_formats = array(
            array(
                'title' => 'Bouton',
                'inline' => 'a',
                'classes' => 'btn-1'
            ),
        );
        $styles['style_formats'] = json_encode($style_formats);

        return $styles;
    }
}
if (!function_exists('juiz_init_editor_styles')) {
    add_action('after_setup_theme', 'juiz_init_editor_styles');
    function juiz_init_editor_styles()
    {
        add_editor_style('assets/css/app.css');
    }
}

// Tiny MCE, add class rte
add_filter('tiny_mce_before_init', 'wpse_editor_styles_class');
function wpse_editor_styles_class($settings)
{
    $settings['body_class'] = 'rte';
    return $settings;
}

// wysywig sup sub
function enable_more_buttons($buttons)
{
    $buttons[] = "superscript";
    $buttons[] = "subscript";

    return $buttons;
}
add_filter("mce_buttons_2", "enable_more_buttons");





/**
 * clean wpml
 */
// remove oembed
function disable_embeds_code_init()
{

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    add_filter('embed_oembed_discover', '__return_false');

    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
    add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');

    // Remove all embeds rewrite rules.
    add_filter('rewrite_rules_array', 'disable_embeds_rewrites');

    // Remove filter of the oEmbed result before any HTTP requests are made.
    remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
}

add_action('init', 'disable_embeds_code_init', 9999);

function disable_embeds_tiny_mce_plugin($plugins)
{
    return array_diff($plugins, array('wpembed'));
}

function disable_embeds_rewrites($rules)
{
    foreach ($rules as $rule => $rewrite) {
        if (false !== strpos($rewrite, 'embed=true')) {
            unset($rules[$rule]);
        }
    }
    return $rules;
}

// remove application/json
function remove_json_api()
{

    // Remove the REST API lines from the HTML Header
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    add_filter('embed_oembed_discover', '__return_false');

    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // Remove all embeds rewrite rules.
    add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
}
add_action('after_setup_theme', 'remove_json_api');

/*
 * Menu and list reset
 */
// remove classes and ids of Walker_Nav_Menu
add_filter('nav_menu_item_id', 'clear_nav_menu_item_id', 10, 3);
function clear_nav_menu_item_id($id, $item, $args)
{
    return "";
}
add_filter('nav_menu_css_class', 'clear_nav_menu_item_class', 10, 3);
function clear_nav_menu_item_class($classes, $item, $args)
{
    return array();
}
// remove classes and ids of wp_list_pages()
add_filter('wp_list_pages', 'remove_page_class');
function remove_page_class($wp_list_pages)
{
    $pattern = '/\<li class="page_item[^>]*>/';
    $replace_with = '<li>';
    return preg_replace($pattern, $replace_with, $wp_list_pages);
}
