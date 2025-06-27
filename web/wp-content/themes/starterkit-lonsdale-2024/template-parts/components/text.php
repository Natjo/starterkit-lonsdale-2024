<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
?>
<div class="rte<?= $classes ?>" <?= $attributes ?>><?= $args['text']; ?></div>