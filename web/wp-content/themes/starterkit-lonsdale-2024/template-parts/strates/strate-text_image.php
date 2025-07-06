<?php
$full = !empty($args["full"]) ? " full" : "";
$reverse = !empty($args["reverse"]) ? " reverse" : "";
//  console($args);
?>
<section <?= options("strate strate-text_image" . $full . $reverse, $args) ?>>
    <?= block::header($args["header"]) ?>

    <div class="strate-content">

        <?= component::title($args["title"], "title-1") ?>

        <?= component::text($args["text"]) ?>

        <?= component::link($args["link"], "cta link link-2"); ?>
    </div>

    <?php component::picture($args["images"], true); ?>

</section>