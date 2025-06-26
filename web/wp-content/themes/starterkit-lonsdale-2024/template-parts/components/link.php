<?php
$link = !empty($args["link"]) ? $args["link"] : [];
$target = !empty($link["target"]) && $link["target"] != "" ? ' target="_blank"' : '';
$classes = !empty($args["classes"]) ? $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? " ".$args["attributes"] : "";
?>

<a href="<?= $link["url"] ?>" class="<?= $classes ?>"<?= $attributes . $target ?>><?= $link["title"] ?></a>