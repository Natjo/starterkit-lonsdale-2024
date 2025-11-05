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

   // show_admin_bar(false);

    add_image_size('415_300', 415, 300, array('center', 'center'));

    add_image_size('400_236', 400, 236, array('center', 'center'));
    add_image_size('620_auto', 620, 0, array('center', 'center'));
    
}

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


/*
 * TINY MCE
 */

// Set formats
function wysiwyg_block_formats($args)
{
    $args['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5';
    return $args;
}
add_filter('tiny_mce_before_init', 'wysiwyg_block_formats');


// Custom Formats
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
                'title' => 'Intro',
                'inline' => 'span',
                'classes' => 'intro'
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
        add_editor_style('assets/styles.css');
    }
}

// Tiny MCE, add class rte
add_filter('tiny_mce_before_init', 'wpse_editor_styles_class');
function wpse_editor_styles_class($settings)
{
    $settings['body_class'] = 'rte mce';
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

// tiny mce Formatage avec les <p>
//add_filter('tiny_mce_before_init', 'prevent_deleting_pTags');
function prevent_deleting_pTags($init)
{
    $init['wpautop'] = false;
    return $init;
}
