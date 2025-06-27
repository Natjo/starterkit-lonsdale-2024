<header class="hero-homepage">
    <div class="container">
        <?= component::title($args['title'], "title-1"); ?>

        <?= component::intro($args['intro']); ?>

        <?= component::link($args['link'], "btn btn-1"); ?>
    </div>

    <?php component::picture($args["images"], "", true); ?>
</header>