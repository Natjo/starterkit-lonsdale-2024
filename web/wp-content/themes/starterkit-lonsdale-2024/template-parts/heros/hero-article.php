<?php

?>
<header class="hero hero-news">
    <div class="hero-content">
        <?= component::title($args['title'], "title-1") ?>

        <?= component::intro($args['intro']); ?>

        <?= component::date($args['date']); ?>
    </div>
        
    <?php component::picture($args["images"], "", true); ?>

</header>