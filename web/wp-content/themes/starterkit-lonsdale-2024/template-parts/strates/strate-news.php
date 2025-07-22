<?php

$card_hx = !empty($args["header"]["title"]) ? 3 : 2;
?>

<section <?= options("strate strate-news", $args) ?>>
    <?= component::header($args["header"]) ?>

    <div class="strate-content">
        <ul>
            <?php foreach ($args["items"] as $item) : ?>
                <li>
                    <?= card::news($item->ID, ["hx" => $card_hx]) ?>
                </li>
            <?php endforeach ?>
        </ul>

        <?= component::link($args["link"], "cta btn btn-1"); ?>
    </div>
</section>