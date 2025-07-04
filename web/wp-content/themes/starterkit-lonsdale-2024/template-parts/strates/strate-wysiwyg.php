<?php
//  console($args);
?>
<section <?= options("strate strate-wysiwyg", $args) ?>>

    <?= block::header($args["header"]) ?>

    <?= component::text($args["text"]) ?>

</section>