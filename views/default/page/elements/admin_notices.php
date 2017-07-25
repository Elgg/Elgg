<?php
/**
 * Lists admin notices
 *
 * @uses $vars['notices'] Array of ElggObject notices
 */

if (!isset($vars['notices'])) {
	// legacy usage
	echo elgg_list_entities([
		'limit' => false,
		'list_class' => 'elgg-admin-notices',
	], 'elgg_get_admin_notices');
	return;
}

echo elgg_view_entity_list($vars['notices'], [
	'list_class' => 'elgg-admin-notices',
]);
