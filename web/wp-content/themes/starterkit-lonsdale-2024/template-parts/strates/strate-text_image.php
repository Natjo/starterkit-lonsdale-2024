<?php
//  console($args);
?>
<section <?= options("strate strate-text_image", $args) ?>>
    <?= block::header($args["header"]) ?>

    <div class="strate-content">
        <div>
            <?= component::title($args["title"], "title-1") ?>

            <?= component::text($args["text"]) ?>

            <?= component::link($args["link"], "cta link link-2"); ?>
        </div>

        <?php component::picture($args["images"], true); ?>
    </div>
</section>