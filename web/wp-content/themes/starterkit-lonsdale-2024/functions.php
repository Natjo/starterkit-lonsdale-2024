<?php
define('THEME_DIR', get_template_directory() . '/');
define('THEME_ASSETS', get_template_directory_uri() . '/assets/');
define('THEME_URL', get_template_directory_uri() . '/');
define('HOME_URL', get_home_url());
define('AJAX_URL', admin_url('admin-ajax.php'));
define('THEME', "/wp-content/themes/".get_template()."/");
define('VERSION', file_get_contents(get_template_directory() . "/assets/version.txt"));

if (ENV_PROD) {
    define('GTAG_KEY', get_field('params_ga_code', 'option'));
} else {
    define('GTAG_KEY', 'AIzaSyCvSv4RSBSEL6zCfuA6XIsMMcQA0cxgBno');
}

if (!ENV_LOCAL) {
    define('ACF_LITE', true);
}

require_once(__DIR__ . '/inc/datatypes.php');
require_once(__DIR__ . '/inc/configuration.php');
require_once(__DIR__ . '/inc/configuration_security.php');

if (!ENV_LOCAL) {
    require_once(__DIR__ . '/inc/acf.php');
}

require_once(__DIR__ . '/inc/methods.php');
require_once(__DIR__ . '/inc/ajax-methods.php');
require_once(__DIR__ . '/inc/strates_helper.php');
require_once(__DIR__ . '/inc/customs/walker.php');
require_once(__DIR__ . '/inc/customs/form.php');
require_once(__DIR__ . '/inc/customs/breadcrumb.php');
require_once(__DIR__ . '/inc/customs/search.php');



function paramsData()
{
    $dataToBePassed = array(
        'ajax_url' => AJAX_URL,
        'theme_url' => THEME_URL,
        'gtag_key' =>  GTAG_KEY,
        'version' => VERSION
    );
    echo json_encode($dataToBePassed);
}

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


/*
 * MAIL
 */

// Php mailer
add_action('phpmailer_init', 'my_phpmailer_configuration');
function my_phpmailer_configuration($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host = 'in-v3.mailjet.com';
    $phpmailer->SMTPAuth = true; // Indispensable pour forcer l'authentification
    $phpmailer->Port = 587;
    $phpmailer->Username = 'a16fb8f8858b28ba57a608c6a9452130';
    $phpmailer->Password = 'f44139b1ddfd76f738181b51e3c50101';
    $phpmailer->SMTPSecure = "tls"; // Sécurisation du serveur SMTP : ssl ou tls
    $phpmailer->From = "mail@lonsdale.fr"; // Adresse email d'envoi des mails
    $phpmailer->FromName = "Site - Valorplast"; // Nom affiché lors de l'envoi du mail
}

// Mailjet create contact
require_once __DIR__ . '/../../../vendor/autoload.php';

use \Mailjet\Resources;

function mailJetAddContact($name, $email)
{
    $mj = new \Mailjet\Client('a16fb8f8858b28ba57a608c6a9452130', 'f44139b1ddfd76f738181b51e3c50101', true, ['version' => 'v3']);
    $body = [
        'IsExcludedFromCampaigns' => "true",
        'Name' => $name,
        'Email' => $email
    ];
    $response = $mj->post(Resources::$Contact, ['body' => $body]);
    //$response->success() && var_dump($response->getData());
}
