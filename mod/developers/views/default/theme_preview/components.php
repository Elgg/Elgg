<?php
/**
 * CSS Objects: list, module, image_block, table, messages
 */

$body = elgg_view('theme_preview/components/image_block');
echo elgg_view_module('theme-sandbox-demo', 'Image Block (.elgg-image-block)', $body);

$body = elgg_view('theme_preview/components/list');
echo elgg_view_module('theme-sandbox-demo', 'List (.elgg-list)', $body);

$body = elgg_view('theme_preview/components/table', array('class' => 'elgg-table'));
echo elgg_view_module('theme-sandbox-demo', 'Table (.elgg-table)', $body);

$body = elgg_view('theme_preview/components/table', array('class' => 'elgg-table-alt'));
echo elgg_view_module('theme-sandbox-demo', 'Table Alternate (.elgg-table-alt)', $body);

$body = elgg_view('theme_preview/components/tagcloud');
echo elgg_view_module('theme-sandbox-demo', 'Tag cloud (.elgg-tagcloud)', $body);

$body = elgg_view('theme_preview/components/tags');
echo elgg_view_module('theme-sandbox-demo', 'Tags (.elgg-tag)', $body);

$body = elgg_view('theme_preview/components/messages');
echo elgg_view_module('theme-sandbox-demo', 'Messages (.elgg-message)', $body);
