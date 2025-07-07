<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
$hx = "h" . $args["hx"];
?>

<<?= $hx; ?> class="title<?= $classes; ?>" <?= $attributes ?>><?= $args['title']; ?></<?= $hx; ?>>