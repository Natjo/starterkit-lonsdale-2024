<?php
//  console($args);
?>
<section <?= options("strate strate-text_image", $args) ?>>
    <div class="container">
        <?= block::header($args["header"]) ?>

        <div class="strate-content">
            <?= component::text($args["text"]) ?>

            <?php component::picture($args["images"], true); ?>
        </div>
    </div>
</section>
