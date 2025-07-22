<?php

//  console($args);
?>
<section <?= options("strate strate-quote", $args) ?>>
    <?= component::header($args["header"]) ?>

    <?= component::blockquote($args["quote"]) ?>
</section>