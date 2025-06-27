<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
?>
<div class="intro<?= $classes ?>" <?= $attributes ?>><?= $args['text']; ?></div>