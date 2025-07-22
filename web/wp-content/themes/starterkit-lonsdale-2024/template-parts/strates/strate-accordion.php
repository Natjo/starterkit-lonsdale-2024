<?php
$column = $args["column"] ? ' column' : '';
// console($args);
?>
<section <?= options("strate strate-accordion" . $column, $args) ?>>
    <?= component::header($args["header"]) ?>

    <?= component::accordion($args["items"]) ?>
</section>