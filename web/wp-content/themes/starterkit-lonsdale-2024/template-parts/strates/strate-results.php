<?php
$cpts = $args["cpts"];
$items = $cpts->items;
$pager = $cpts->pager;
$query = $cpts->query;

if ($query > 0) {
    $url = "?s=" . $query . "&paged=";
} else {
    $url = get_post_field('post_name') . "/page/";
}

?>

<section class="strate strate-results" data-module="strates/results" data-url="<?= $url ?>" data-query="<?= $query ?>" data-nonce="<?= wp_create_nonce("results_nonce"); ?>">
    <?php if ($query > 0) : ?>
        <header>
            <?php component::search(["label" => "Search"]); ?>

            <?php
            $total_posts = $pager['total_posts'];
            if ($total_posts > -1) : ?>
                <?php
                $search = "« " . $query . " »";
                $plurial = $total_posts > 1 ? "s" : "";
                if ($total_posts) {
                    $title = $total_posts . " résultat" . $plurial . " correspondent à votre recherche " . $search;
                } else {
                    $title = "Aucun article correspond à votre recherche " . $search;
                }
                ?>
                <h1><?= $title; ?></h1>
            <?php else : ?>
                <div>Saisir une recherche</div>
            <?php endif ?>
        </header>
    <?php endif ?>

    <ul class="list">
        <?php if (!empty($items)) : ?>
            <?php foreach ($items as $item) : ?>
                <?php $post_type = "news"; ?>
                <li><?php card::$post_type($item); ?></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <?php component::pagination($pager, "/" . $url); ?>
</section>