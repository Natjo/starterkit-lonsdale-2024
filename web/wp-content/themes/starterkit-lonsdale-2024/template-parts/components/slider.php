 <?php
    $classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
    $attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
    ?>

 <div class="slider<?= $classes ?>">
     <button class="slider-btn prev btn btn-1">prev</button>
     <button class="slider-btn next btn btn-1">next</button>

     <div class="slider-content" role="list">
         <?php foreach ($args["items"] as $item) : ?>
             <div class="item" role="listitem">
                 <?php
                    $card = $item["card"]["name"];
                    $sizes = !empty($item["card"]["sizes"]) ? ["sizes" => $item["card"]["sizes"]]  : [];
                    card::$card($item["id"], $sizes);
                    ?>
             </div>
         <?php endforeach ?>
     </div>
 </div>