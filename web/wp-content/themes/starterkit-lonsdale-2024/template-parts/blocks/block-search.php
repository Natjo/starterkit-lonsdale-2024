 <?php
    $classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
    $attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
    ?>

 <form class="block-search<?= $classes; ?>" method="post" action="/" <?= $attributes ?>>
     <input id="search-input" type="text" name="s"  required pattern="\S+.*">
     <label for="search-input"><?= $args["label"] ?></label>
     <button type="submit"><?= component::icon('search', 30, 21) ?></button>
 </form>