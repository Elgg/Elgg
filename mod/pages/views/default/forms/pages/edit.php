<?php
/**
 * Page edit form body
 */

elgg_deprecated_notice("The form 'pages/edit' has been deprecated, use 'page/edit'", '7.1');

if (!isset($vars['fields'])) {
	$vars['fields'] = elgg()->fields->get('object', 'page');
}

echo elgg_view('forms/page/edit', $vars);
