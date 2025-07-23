<?php
/*
Template Name: Contact
*/

get_header();
get_template_part('template-parts/common/header_nav');

$mail = formulaire_contact();
$mail_send = !empty($mail) && empty($mail['error']) ? true : false;
$mail_error = !empty($mail['error']) ? true : false;
?>

<main id="main" role="main" tabindex="-1" class="page-contact">
    <?php hero::flexible(); ?>

    <section class="strate strate-contact" data-view="form-contact" data-module="strates/contact">
        <?php if ($mail_send === true) : ?>
            <div class="msg valid">
                message envoyé
            </div>
        <?php else : ?>
            <?php if ($mail_error === true) : ?>
                <div class="msg error">
                    <?= $mail['msg'] ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!$mail_send) : ?>
            <form class="form" action="/contact" method="post" enctype='multipart/form-data' novalidate data-mandatory="Vous devez remplir ce champs">
                <div class="container">
                    <?php wp_nonce_field('contact', 'contact_nonce'); ?>

                    <fieldset>
                        <h2>Informations générales</h2>

                        <div class="field">
                            <label for="contact-infos">Vous êtes*</label>
                            <select id="contact-infos" name="contact-infos" required aria-describedby="error-infos" data-mandatory="Vous devez sélectionner un item">
                                <option value="" hidden>Sélectionnez...</option>
                                <option value="1">Une collectivité territoriale</option>
                                <option value="2">Un centre de tri</option>
                                <option value="3">Une entreprise</option>
                                <option value="3">Autre</option>
                            </select>
                        </div>

                        <div class="field">

                            <input name="contact-name" type="text" id="contact-name" placeholder="" required aria-describedby="error-name" required>
                            <label class="placeholder" for="contact-name">Nom</label>
                        </div>

                        <div class="field">
                            <input name="contact-email" type="email" id="contact-email" placeholder="" required aria-describedby="error-email">
                            <label class="placeholder" for="contact-email">Email</label>
                        </div>

                        <div class="field">
                            <textarea name="contact-msg" id="contact-msg" placeholder="" aria-describedby="error-msg"></textarea>
                            <label class="placeholder" for="contact-msg">Votre message</label>
                        </div>

                        <div class="field checkbox">
                            <input type="checkbox" name="contact-consent" id="contact-consent" required aria-describedby="error-optin">
                            <label for="contact-consent" class="label-checkbox rte">J'accepte</label>
                        </div>

                        <div class="action">
                            <button type="submit" class="btn btn-1">Envoyer</button>
                        </div>
                    </fieldset>
                </div>
            </form>
        <?php endif; ?>
    </section>
</main>

<?php
get_footer();
