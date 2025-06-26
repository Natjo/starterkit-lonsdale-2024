<?php

$width = $args["width"];
$height = $args["height"];
$name =  $args["name"];
$url = $args["url"];
?>
<svg class="icon" width="<?= $width ?>" height="<?= $height ?>" aria-hidden="true" viewBox="0 0 <?= $width ?> <?= $height ?>">
    <use xlink:href="<?= $url ?>img/icons.svg#<?= $name ?>"></use>
</svg>