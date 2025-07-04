<?php

//console($args);
?>

<section <?= options("strate strate-news", $args) ?>>
    <?= block::header($args["header"]) ?>

    <div class="strate-content">
        <ul>
            <?php foreach ($args["items"] as $item) : ?>
                <li>
                    <?= card::news($item->ID) ?>
                </li>
            <?php endforeach ?>
        </ul>

        <?= component::link($args["link"], "cta btn btn-1"); ?>
    </div>
</section>