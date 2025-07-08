<?php
$column = $args["column"] ? ' column' : '';
//  console($args);
?>
<section <?= options("strate strate-wysiwyg" . $column, $args) ?>>

    <?= block::header($args["header"]) ?>

    <?= component::text($args["text"]) ?>
</section>