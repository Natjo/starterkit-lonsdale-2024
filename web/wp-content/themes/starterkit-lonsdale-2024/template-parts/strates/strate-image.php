<?php


?>

<section <?= options("strate strate-image", $args) ?>>
    <?= component::header($args["header"]) ?>

    <?= component::picture($args["images"], true); ?>
</section>