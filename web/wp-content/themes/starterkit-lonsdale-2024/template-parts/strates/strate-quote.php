<?php

//  console($args);
?>
<section <?= options("strate strate-quote", $args) ?>>
    <?= block::header($args["header"]) ?>

    <?= component::blockquote($args["quote"]) ?>
</section>