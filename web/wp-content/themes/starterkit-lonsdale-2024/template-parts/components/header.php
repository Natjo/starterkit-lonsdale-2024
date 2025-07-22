<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
?>

<header class="header<?= $classes; ?>" <?= $attributes ?>>
    <? component::title(2, $args['title'], "title-1") ?>

    <? component::intro($args['text'],"rte") ?>

    <? component::link($args['cta'], "cta btn btn-1") ?>
</header>
