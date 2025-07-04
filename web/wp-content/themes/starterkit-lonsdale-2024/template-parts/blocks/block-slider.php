 <?php
    $classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
    $attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
    ?>

 <div class="slider<?= $classes ?>">

     <button class="slider-btn prev">prev</button>
     <button class="slider-btn next">next</button>

     <div class="slider-content" role="list">
         <?php foreach ($args["items"] as $item) : ?>
             <div class="item">
                 <?php
                    $card = $args["card"];
                    card::$card($item);
                    ?>
             </div>
         <?php endforeach ?>
     </div>
 </div>