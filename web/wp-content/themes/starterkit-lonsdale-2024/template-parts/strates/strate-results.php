<?php
$type = $args["type"];
$cpts = $args["cpts"];
$items = $cpts->items;
$pager = $cpts->pager;
$query = $cpts->query;

$post_id = url_to_postid(wp_get_referer());
$post_name = get_post_field('post_name', $post_id);

if ($query > 0) {
    if ($type == "pagination") {
        $url =  "/" . "?s=" . $query . "&paged=";
    } else {
        $url =  "/" . "?s=" . $query;
    }
} else {
    $url =  "/" . $post_name . "/page/";
}
?>

<section class="strate strate-results" data-module="strates/results"
    data-type="<?= $type ?>"
    data-total_pages="<?= $pager["total_pages"] ?>"
    data-paged="<?= $pager["current_page"] ?>"
    data-url="<?= $url ?>"
    data-query="<?= $query ?>"
    data-nonce="<?= wp_create_nonce("results_nonce"); ?>">
    <?php if ($query > 0) : ?>
        <header>
            <?php
            $total_posts = $pager['total_posts'];
            if ($total_posts > -1) : ?>
                <?php
                $search = "« " . $query . " »";
                $plurial = $total_posts > 1 ? "s" : "";
                if ($total_posts) {
                    $title = $total_posts . " résultat" . $plurial . " correspondent à votre recherche " . $search;
                } else {
                    $query = "Search";
                    $title = "Aucun article correspond à votre recherche " . $search;
                }
                ?>
                <h1><?= $title; ?></h1>
            <?php else : ?>
                <div>Saisir une recherche</div>
            <?php endif ?>

            <?php component::search(["label" => $query]); ?>
        </header>
    <?php endif ?>

    <form action="" class="filters form">
        <fieldset>
            <div class="field select">
                <select name="" id="">
                    <option value="">Mr</option>
                    <option value="">Ms</option>
                </select>
            </div>
            <div class="field checkbox">
                <input id="pop" type="checkbox">
                <label for="pop">rouge</label>
            </div>
        </fieldset>
    </form>

    <ul class="list">
        <?php if (!empty($items)) : ?>
            <?php foreach ($items as $item) : ?>
                <?php $post_type = "news"; ?>
                <li><?php card::$post_type($item); ?></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <?php if ($type == "more" && $total_posts > 0) : ?>
        <button class="btn-more btn btn-1">more</button>
    <?php endif; ?>

    <?php if ($type == "pagination") : ?>
        <?php component::pagination($pager, $url); ?>
    <?php endif; ?>
</section>