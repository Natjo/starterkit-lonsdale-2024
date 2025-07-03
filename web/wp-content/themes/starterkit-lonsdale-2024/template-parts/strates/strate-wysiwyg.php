<?php
//  console($args);
?>
<section <?= options("strate strate-wysiwyg", $args) ?>>
    <div class="container">
        <?= block::header($args["header"]) ?>

        <?= component::text($args["text"]) ?>
    </div>
</section>