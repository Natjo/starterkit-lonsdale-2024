<?php
define('THEME_DIR', get_template_directory() . '/');
define('THEME_ASSETS', get_template_directory_uri() . '/assets/');
define('THEME_URL', get_template_directory_uri() . '/');
define('HOME_URL', get_home_url());
define('AJAX_URL', admin_url('admin-ajax.php'));
define('THEME', "/wp-content/themes/" . get_template() . "/");
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
require_once(__DIR__ . '/inc/methods-front.php');
require_once(__DIR__ . '/inc/ajax-methods.php');
require_once(__DIR__ . '/inc/strates_helper.php');
require_once(__DIR__ . '/inc/blocks_helper.php');
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


/**
 * SEO title and desc
 */
function lsd_seo()
{
    remove_action('wp_head', '_wp_render_title_tag', 1);

    $title = get_field('options-seo-title', 'options');
    $desc = get_field('options-seo-desc', 'options');

    if (empty($title)) {
        $title = get_bloginfo('name');
    }

    if (!is_front_page()) {
        $title =  $title . " | " . get_the_title();
    }

    $markup = '<title>' . $title  . '</title>' . "\n";

    if (!empty($desc)) {
        $markup .= '<meta name="description" content="' . $desc . '">' . "\n";
    }

    return $markup;
}


// Mode dark/contrast ...
function mode()
{
    if (isset($_COOKIE['mode']) && "darkmode" == $_COOKIE['mode']) {
        echo ' data-mode="darkmode"';
    } elseif (isset($_COOKIE['mode']) && "contrastmode" == $_COOKIE['mode']) {
        echo ' data-mode="contrastmode"';
    }
}

/* options des strates */
function options($args)
{
    $margin = !empty($args["options"]["margin"]) ? " margin-" . $args["options"]["margin"] : "";
    return $margin;
}
