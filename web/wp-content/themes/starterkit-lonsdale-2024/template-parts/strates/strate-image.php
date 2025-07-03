<?php

//console($args);
?>


<section <?= options("strate strate-image", $args) ?>>
    <div class="container">
        <?= block::header($args["header"]) ?>
    </div> 
    
    <?php component::picture($args["images"], true); ?>
</section>