<?php
$classes = !empty($args["classes"]) ? $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
?>

<button class="<?= $classes ?>" <?= $attributes ?>><?= $args["title"] ?></button>