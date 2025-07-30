<?php
/*
Template Name: Contact
*/

get_header();
get_template_part('template-parts/common/header_nav');

?>

<main id="main" role="main" tabindex="-1" class="page-contact">
    <?php hero::page(); ?>

    <section class="strate strate-contact" data-view="form-contact" data-module="strates/contact">
        <form class="form" action="contact" method="post" enctype='multipart/form-data' role="form" aria-label="Contact information" novalidate data-mandatory="Vous devez remplir ce champs" data-nonce="<?= wp_create_nonce("contact_nonce"); ?>">
            <div class="container">
                <?php wp_nonce_field('contact', 'contact_nonce'); ?>

                <fieldset>
                    <h2 class="legend">Informations générales</h2>

                    <div class="field left">
                        <label for="contact-infos">Vous êtes*</label>
                        <select id="contact-infos" name="contact-infos" required aria-describedby="error-infos" data-mandatory="Vous devez sélectionner un item">
                            <option value="" hidden>Sélectionnez...</option>
                            <option value="1">Une collectivité territoriale</option>
                            <option value="2">Un centre de tri</option>
                            <option value="3">Une entreprise</option>
                            <option value="3">Autre</option>
                        </select>
                    </div>

                    <div class="field left">
                        <input data-mandatory="popo" name="contact-name" type="text" id="contact-name" placeholder="" required aria-describedby="error-name" required>
                        <label class="placeholder" for="contact-name">Nom</label>
                    </div>
                    <div class="field right">
                        <input name="contact-lastname" type="text" id="contact-lastname" placeholder="" aria-describedby="error-name">
                        <label class="placeholder" for="contact-name">Prénom</label>
                    </div>

                    <div class="field left">
                        <input data-typemismatch="Wesh l'email" name="contact-email" type="email" id="contact-email" placeholder="" aria-describedby="error-email">
                        <label class="placeholder" for="contact-email">Email</label>
                    </div>
                    <div class="field right">
                        <label for="" class="placeholder">Tel*</label>
                        <input type="tel" placeholder="(+xx) xxxxxxxx" pattern="^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$" aria-describedby="error-tel" data-patternmismatch="Tel number must be (+xx) xxxxxxxx" autocomplete="tel">
                    </div>

                    <div class="field left">
                        <label class="placeholder">Age*</label>
                        <input type="number" min="10" max="100" placeholder="Between 10 and 100" name="age" aria-describedby="error-age">
                    </div>
                    <div class="field right">
                        <label for="" class="placeholder">Date*</label>
                        <input type="date" placeholder="jj/mm/aaaa" aria-describedby="error-date">
                    </div>

                    <div class="field">
                        <textarea ame="contact-msg" required id="contact-msg" placeholder="" aria-describedby="error-msg"></textarea>
                        <label class="placeholder" for="contact-msg">Votre message</label>
                    </div>

                    <div class="field checkbox" role="group" aria-labelledby="option-label">
                        <label id="option-label">Option</label>
                        <ul>
                            <li>
                                <input id="option-0" type="checkbox" name="option-0" value="" aria-describedby="error-option">
                                <label for="option-0">Option 1</label>
                            </li>
                            <li>
                                <input id="option-1" type="checkbox" name="option-1" value="" aria-describedby="error-option">
                                <label for="option-1">Option 2</label>
                            </li>
                            <li>
                                <input id="option-2" type="checkbox" name="option-2" value="" aria-describedby="error-option">
                                <label for="option-2">Option 3</label>
                            </li>
                        </ul>
                    </div>

                    <div class="field radio" role="group" aria-labelledby="country-label" data-mandatory="popopo">
                        <label id="country-label">Country*</label>
                        <ul>
                            <li>
                                <input id="country-0" type="radio" name="test[]" value="" required aria-describedby="error-country">
                                <label for="country-0">France</label>
                            </li>
                            <li>
                                <input id="country-1" type="radio" name="test[]" value="" required aria-describedby="error-country">
                                <label for="country-1">England</label>
                            </li>
                            <li>
                                <input id="country-2" type="radio" name="test[]" value="" required aria-describedby="error-country">
                                <label for="country-2">Russia</label>
                            </li>
                        </ul>
                    </div>

                    <div class="field checkbox">
                        <input type="checkbox" name="contact-consent" id="contact-consent" required aria-describedby="error-optin">
                        <label for="contact-consent" class="label-checkbox rte">J'accepte les conditions générales </label>
                    </div>

                    <div class="field msg"></div>

                    <div class="field action">
                        <button type="submit" class="btn btn-1">Envoyer</button>
                    </div>
                </fieldset>
            </div>
        </form>
    </section>
</main>

<?php
get_footer();
