<?php

//console($args);
?>

<section <?= options("strate strate-slider", $args) ?> data-module="strates/slider">
    <?= component::header($args["header"]) ?>

    <?= component::slider($args["items"], "myslider") ?>
</section>