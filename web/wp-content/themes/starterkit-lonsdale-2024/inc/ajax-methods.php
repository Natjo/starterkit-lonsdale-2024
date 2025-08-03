<?php
add_action('wp_ajax_contact', 'contact_callback');
add_action('wp_ajax_nopriv_contact', 'contact_callback');
function contact_callback()
{
    checkNonce('contact_nonce');

    $response['msg'] = "Message envoyé  vec succès";

    $response['msg'] = "Message envoyé  avec succès";

    wp_send_json($response);
}

add_action('wp_ajax_results', 'results_callback');
add_action('wp_ajax_nopriv_results', 'results_callback');
function results_callback()
{
    checkNonce('results_nonce');

    ob_start();
    strate::results('news',$_POST["paged"],$_POST["query"]);
    $response['content'] = ob_get_clean();

    $response['msg'] = "Message envoyé  vec succès";

    $response['msg'] = "Message envoyé  avec succès";

    wp_send_json($response);
}
