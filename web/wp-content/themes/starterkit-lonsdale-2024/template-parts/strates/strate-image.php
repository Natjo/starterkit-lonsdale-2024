<?php

//console($args);
?>

<section <?= options("strate strate-image", $args) ?>>
    <?= component::header($args["header"]) ?>

    <?php component::picture($args["images"], true); ?>
</section>