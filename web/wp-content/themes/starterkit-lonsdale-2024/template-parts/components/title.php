<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
?>

<h1 class="title<?= $classes; ?>" <?= $attributes ?>><?= $args['title']; ?></h1>