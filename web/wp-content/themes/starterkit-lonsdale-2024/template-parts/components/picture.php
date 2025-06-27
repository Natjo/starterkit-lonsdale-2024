<?php
$imgMobile = !empty($args['mobile']) ? $args['mobile'] : null;
$imgDesktop = !empty($args['desktop']) ? $args['desktop'] : null;
$breakpoint = $args['breakpoint'];
$lazy = !empty($args["lazy"]) ? ' loading="lazy"' : "";
$alt = !empty($imgDesktop["alt"]) ? ' alt="'.$imgDesktop["alt"].'"' : "";
$classes = !empty($args["classes"]) ? ' class="' . $args["classes"] . '"' : "";
?>

<picture <?= $classes ?>>
    <?php if ($imgMobile) : ?>
        <?php if (!empty($imgMobile["webp"])) : ?>
            <source  width="<?= $imgMobile["width"] ?>" height="<?= $imgMobile["height"] ?>" srcset="<?= $imgMobile["webp"] ?>" media="(max-width: <?= $breakpoint - 1 ?>px)" type="image/webp">
        <?php endif ?>
        <source width="<?= $imgMobile["width"] ?>" height="<?= $imgMobile["height"] ?>" srcset="<?= $imgMobile["src"] ?>" media="(max-width: <?= $breakpoint - 1 ?>px)" type="image/jpg">
    <?php endif ?>

    <?php if ($imgDesktop) : ?>
        <?php if (!empty($imgDesktop["webp"])) : ?>
            <source width="<?= $imgDesktop["width"] ?>" height="<?= $imgDesktop["height"] ?>" srcset="<?= $imgDesktop["webp"] ?>" media="(min-width: <?= $breakpoint ?>px)" type="image/webp">
        <?php endif ?>

        <source width="<?= $imgDesktop["width"] ?>" height="<?= $imgDesktop["height"] ?>" srcset="<?= $imgDesktop["src"] ?>" media="(min-width: <?= $breakpoint ?>px)" type="image/jpg">
    <?php endif ?>

    <img src="<?= $imgDesktop["src"] ?>"  <?= $alt ?> width="<?= $imgDesktop["width"] ?>" height="<?= $imgDesktop["height"] ?>" <?= $lazy ?>>

</picture>