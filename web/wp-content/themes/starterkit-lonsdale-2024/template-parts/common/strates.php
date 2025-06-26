<?php
$pageId = isset($args["page_id"]) ? $args["page_id"] : get_the_ID();
$aStrates = get_field('strates', $pageId);

if (isset($aStrates) && !empty($aStrates)) {
    foreach ($aStrates as $aStrate) {
        switch ($aStrate['acf_fc_layout']) {
            case 'strate-image':
                get_template_part('template-parts/strates/image', '', Strate_Helper::image($aStrate));
                break;
            case 'strate-wysiwyg':
                get_template_part('template-parts/strates/wysiwyg', '', Strate_Helper::wysiwyg($aStrate));
                break;
        }
    }
}
