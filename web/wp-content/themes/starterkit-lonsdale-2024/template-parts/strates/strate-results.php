<?php

$currentSearch = -1;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$postsPerPage = get_option('posts_per_page');
$argsPosts = getSearchCptNews(["s" => $currentSearch], $paged, $postsPerPage);
?>

<section <?= options("strate strate-results", $args) ?>>
    <ul class="list">
        <?php if (!empty($argsPosts['items'])) : ?>
            <?php foreach ($argsPosts['items'] as $item) : ?>
                <?php card::news($item); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <?php pager($argsPosts['pager'], "/" . get_post_field('post_name') . "/page/"); ?>
</section>