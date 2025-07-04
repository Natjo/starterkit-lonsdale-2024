<?php
$pageId = isset($args["page_id"]) ? $args["page_id"] : get_the_ID();
$aStrates = get_field('strates', $pageId);

if (isset($aStrates) && !empty($aStrates)) {
    foreach ($aStrates as $aStrate) {
        switch ($aStrate['acf_fc_layout']) {
            case 'strate-image':
                get_template_part('template-parts/strates/strate', 'image', Strate_Helper::image($aStrate));
                break;
            case 'strate-wysiwyg':
                get_template_part('template-parts/strates/strate', 'wysiwyg', Strate_Helper::wysiwyg($aStrate));
                break;
            case 'strate-text_image':
                get_template_part('template-parts/strates/strate', 'text_image', Strate_Helper::text_image($aStrate));
                break;
            case 'strate-news':
                get_template_part('template-parts/strates/strate', 'news', Strate_Helper::news($aStrate));
                break;
                           case 'strate-slider':
                get_template_part('template-parts/strates/strate', 'slider', Strate_Helper::slider($aStrate));
                break;
        }
    }
}
