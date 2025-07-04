<header class="hero hero-homepage">
    <div class="hero-content">
        <?= component::title($args['title'], "title-1"); ?>

        <?= component::intro($args['intro']); ?>

        <?= component::link($args['link'], "btn btn-1"); ?>
    </div>

    <?php component::picture($args["images"], "", true); ?>
</header>