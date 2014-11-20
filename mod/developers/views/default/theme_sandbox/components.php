<?php
/**
 * CSS Objects: list, module, image_block, table, messages
 */

$body = elgg_view('theme_sandbox/components/image_block');
echo elgg_view_module('theme-sandbox-demo', 'Image Block (.elgg-image-block)', $body);

$body = elgg_view('theme_sandbox/components/list');
echo elgg_view_module('theme-sandbox-demo', 'List (.elgg-list)', $body);

$body = elgg_view('theme_sandbox/components/table', array('class' => 'elgg-table'));
echo elgg_view_module('theme-sandbox-demo', 'Table (.elgg-table)', $body);

$body = elgg_view('theme_sandbox/components/table', array('class' => 'elgg-table-alt'));
echo elgg_view_module('theme-sandbox-demo', 'Table Alternate (.elgg-table-alt)', $body);

$body = elgg_view('theme_sandbox/components/tags');
echo elgg_view_module('theme-sandbox-demo', 'Tags (.elgg-tag)', $body);

$body = elgg_view('theme_sandbox/components/messages');
echo elgg_view_module('theme-sandbox-demo', 'Messages (.elgg-message)', $body);
