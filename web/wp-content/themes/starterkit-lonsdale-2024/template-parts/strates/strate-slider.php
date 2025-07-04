<?php

//console($args);
?>


<section <?= options("strate strate-slider", $args) ?> data-module="strates/slider">
    <?= block::header($args["header"]) ?>

    <?= block::slider($args["card"], $args["items"], "myslider") ?>
</section>