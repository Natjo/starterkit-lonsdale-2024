<?php
$images = $args['images'];
?>

<header class="hero-homepage">
    <div class="container">
        <?php if (!empty($args['title'])) : ?>
            <h1 class="title-1"><?= $args['title']; ?></h1>
        <?php endif; ?>
        <?php if (!empty($args['intro'])) : ?>
            <div class="intro"><?= $args['intro']; ?></div>
        <?php endif; ?>
        <?php if (!empty($args['cta']['label']) && !empty($args['cta']['link'])) : ?>
            <a class="btn btn-1" href="<?= $args['cta']['link']; ?>">
                <?= $args['cta']['label']; ?>
            </a>
        <?php endif; ?>
    </div>
    <?php component::picture($args["images"], "", true); ?>

</header>