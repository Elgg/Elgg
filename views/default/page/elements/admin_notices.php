<?php
/**
 * Lists admin notices
 *
 * @uses $vars['notices'] Array of ElggObject notices
 */
if (!isset($vars['admin_notices'])) {
	return;
}

echo elgg_view_entity_list($vars['admin_notices'], [
	'list_class' => 'elgg-admin-notices',
]);
