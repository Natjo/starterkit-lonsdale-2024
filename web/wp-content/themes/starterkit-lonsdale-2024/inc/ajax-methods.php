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
