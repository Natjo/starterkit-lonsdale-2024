<?php
$classes = !empty($args["classes"]) ? $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";

?>
  
<blockquote class="blockquote">
    <p>
       <?= $args["text"] ?>
    </p>

    <footer><cite><?= $args["cite"]["name"] ?> <?= $args["cite"]["last_name"] ?>, <?= $args["cite"]["function"] ?></cite></footer>

</blockquote>