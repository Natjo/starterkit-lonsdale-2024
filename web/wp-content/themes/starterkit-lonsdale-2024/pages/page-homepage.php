<?php
/*
Template Name: Page Homepage
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');

$pageID =  get_the_ID();

?>

<main id="main" role="main" tabindex="-1" class="page-homepage">
    <header class="hero-home" data-module="heros/hero-home">

    </header>
</main>

<?php
get_footer();
