<?php
/**
 * CSS Objects: list, module, image_block, table, messages
 */

$body = elgg_view('theme_sandbox/components/image_block');
echo elgg_view_module('aside', 'Image Block (.elgg-image-block)', $body);

$body = elgg_view('theme_sandbox/components/image_block_alt');
echo elgg_view_module('aside', 'Image Block (.elgg-image-block) with .elgg-image-alt', $body);

$body = elgg_view('theme_sandbox/components/list');
echo elgg_view_module('aside', 'List (.elgg-list)', $body);

$body = elgg_view('theme_sandbox/components/summary_listing');
echo elgg_view_module('aside', 'Summary Listing (object/elements/summary)', $body);

$body = elgg_view('theme_sandbox/components/full_listing');
echo elgg_view_module('aside', 'Full Listing (object/elements/full)', $body);

$body = elgg_view('theme_sandbox/components/table', ['class' => 'elgg-table']);
echo elgg_view_module('aside', 'Table (.elgg-table)', $body);

$body = elgg_view('theme_sandbox/components/table', ['class' => 'elgg-table-alt']);
echo elgg_view_module('aside', 'Table Alternate (.elgg-table-alt)', $body);

$body = elgg_view('theme_sandbox/components/tags');
echo elgg_view_module('aside', 'Tags (.elgg-tag)', $body);

$body = elgg_view('theme_sandbox/components/messages');
echo elgg_view_module('aside', 'Messages (.elgg-message)', $body);

$body = elgg_view('theme_sandbox/components/tabs');
echo elgg_view_module('aside', 'Inline/Ajax Tabs (.elgg-tabs-component)', $body);
