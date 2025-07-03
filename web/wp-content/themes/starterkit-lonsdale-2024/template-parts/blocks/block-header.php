<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
?>

<header class="block-header<?= $classes; ?>" <?= $attributes ?>>
    <? component::title($args['title'], "title-1") ?>

    <? component::intro($args['text']) ?>

    <? component::link($args['cta'], "cta btn btn-1") ?>
</header>
