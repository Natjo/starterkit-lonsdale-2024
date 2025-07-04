<?php

//console($args);
?>

<section <?= options("strate strate-image", $args) ?>>
    <?= block::header($args["header"]) ?>

    <?php component::picture($args["images"], true); ?>
</section>