<?php
$imgMobile = !empty($args['mobile']) ? $args['mobile'] : null;
$imgDesktop = !empty($args['desktop']) ? $args['desktop'] : null;
$breakpoint = $args['breakpoint'];
$lazy = !empty($args["lazy"]) ? ' loading="lazy"' : "";
$alt = !empty($imgDesktop["alt"]) ? ' alt="' . $imgDesktop["alt"] . '"' : "";
$classes = !empty($args["classes"]) ? ' class="' . $args["classes"] . '"' : "";


$media = "";
if (!empty($imgMobile)) {
    $media = ' media="(min-width:' . $breakpoint . 'px)"';
    $media_mobile = ' media="(max-width:' . ($breakpoint - 1) . 'px)"';
}


?>

<picture <?= $classes ?>>
    <?php if ($imgMobile) : ?>
        <?php if (!empty($imgMobile["webp"])) : ?>
            <source width="<?= $imgMobile["width"] ?>" height="<?= $imgMobile["height"] ?>" srcset="<?= $imgMobile["webp"] ?>" <?= $media_mobile ?> type="image/webp">
        <?php endif ?>
        <source width="<?= $imgMobile["width"] ?>" height="<?= $imgMobile["height"] ?>" srcset="<?= $imgMobile["src"] ?>" <?= $media_mobile ?> type="image/jpg">
    <?php endif ?>

    <?php if ($imgDesktop) : ?>
        <?php if (!empty($imgDesktop["webp"])) : ?>
            <source width="<?= $imgDesktop["width"] ?>" height="<?= $imgDesktop["height"] ?>" srcset="<?= $imgDesktop["webp"] ?>" <?= $media ?> type="image/webp">
        <?php endif ?>

        <source width="<?= $imgDesktop["width"] ?>" height="<?= $imgDesktop["height"] ?>" srcset="<?= $imgDesktop["src"] ?>" <?= $media ?> type="image/jpg">
    <?php endif ?>

    <img src="<?= $imgDesktop["src"] ?>" <?= $alt ?> width="<?= $imgDesktop["width"] ?>" height="<?= $imgDesktop["height"] ?>" <?= $lazy ?>>

</picture>