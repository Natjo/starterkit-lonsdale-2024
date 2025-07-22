<?php

$pageId = isset($args["page_id"]) ? $args["page_id"] : get_the_ID();
$aBlocks = get_field($args["name"], $pageId)["blocks"];

if (isset($aBlocks) && !empty($aBlocks)) {

    foreach ($aBlocks as $block) {

        switch ($block['acf_fc_layout']) {
            case 'title':
                component::title($block["title-hx"], $block["title-title"], "title " . $block["title-style"]);
                break;
            case 'text':
                component::text($block["text-text"]);
                break;
            case 'cta':
                $style =  $block["cta-type"] == "btn" ? $block["cta-btn-style"] : $block["cta-link-style"];
                component::link($block["cta-link"], $block["cta-type"] . " " . $style);
                break;
            case 'image':
                // console($block["image-desktop"]);
                /*  $block_image = [
                    "desktop" => [
                        "id" => $block["image-desktop"]["ID"]
                    ]
                ];*/
                //  $images =  Helper::images($aStrate["block-image"], "620_auto");
                //component::text($block["text-text"]);
                break;
            case 'pushes':
                $args = [
                    "items" => $block["pushes-items"]
                ];
                component::pushes($args["items"]);
                break;
            case 'accordion':
                $args = [
                    "items" => $block["accordion-items"]
                ];

                component::accordion($args["items"]);
                break;
        }
    }
}
