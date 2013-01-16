<?php
/**
 * CSS Objects: list, module, image_block, table, messages
 */

$body = elgg_view('theme_preview/components/image_block');
echo elgg_view_module('info', 'Image Block (.elgg-image-block)', $body);

$body = elgg_view('theme_preview/components/list');
echo elgg_view_module('info', 'List (.elgg-list)', $body);

$body = elgg_view('theme_preview/components/table', array('class' => 'elgg-table'));
echo elgg_view_module('info', 'Table (.elgg-table)', $body);

$body = elgg_view('theme_preview/components/table', array('class' => 'elgg-table-alt'));
echo elgg_view_module('info', 'Table Alternate (.elgg-table-alt)', $body);

$body = elgg_view('theme_preview/components/tagcloud');
echo elgg_view_module('info', 'Tag cloud (.elgg-tagcloud)', $body);

$body = elgg_view('theme_preview/components/tags');
echo elgg_view_module('info', 'Tags (.elgg-tag)', $body);

$body = elgg_view('theme_preview/components/messages');
echo elgg_view_module('info', 'Messages (.elgg-message)', $body);