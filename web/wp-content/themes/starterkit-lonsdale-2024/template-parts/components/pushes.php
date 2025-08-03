<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
?>

<div class="pushes<?= $classes ?>">
    <ul>
        <?php foreach ($args["items"] as $item) : ?>
            <li>
                <?php
                $post_type = $item->post_type;
                if (get_page_template_slug($item->ID) == "pages/page-flexible.php") {
                    $post_type = "flexible";
                }
                ?>
                <?php

                card::$post_type($item->ID)
                ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>