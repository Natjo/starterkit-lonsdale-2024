<?php
  //console($args);
?>
<section <?= options("strate strate-accordion", $args) ?>>

    <?= block::header($args["header"]) ?>

    <?= component::accordion($args["items"]) ?>

</section>