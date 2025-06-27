<?php
//console($args["header"]);
?>
<section class="strate strate-wysiwyg<?= options($args) ?>">
    <div class="container">
        <?= block::header($args["header"]) ?>

        <?= component::text($args["text"]) ?>
    </div>
</section>