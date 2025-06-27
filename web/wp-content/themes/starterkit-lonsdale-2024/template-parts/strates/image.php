<?php

//console($args);
?>


<section <?= options("strate strate-image", $args) ?>>
    <div class="container">
        <?php component::picture($args["images"], true); ?>
    </div>
</section>