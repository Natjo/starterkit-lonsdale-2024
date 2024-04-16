<?php get_template_part('template-parts/general/block', 'footer'); ?>

<script id="appjs" async type="module" src="<?= THEME; ?>assets/js/app.js?v=<?= VERSION ?>" data-ajax_url="<?= AJAX_URL ?>" data-theme_url="<?= THEME_URL ?>" data-gtag_key="<?= GTAG_KEY ?>" data-version="<?= VERSION ?>"></script>

<?php  wp_footer(); ?>

</body>

</html>