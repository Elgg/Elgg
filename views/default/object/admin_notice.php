<?php
/**
 * A persistent admin notice to be displayed on all admin pages until cleared.
 */

$entity = elgg_extract('entity', $vars);
if (!elgg_instanceof($entity, 'object', 'admin_notice')) {
	return;
}

elgg_require_js('elgg/admin_notices');

$message = $entity->description;

$delete = elgg_view('output/url', [
	'href' => "action/admin/delete_admin_notice?guid={$entity->guid}",
	'text' => elgg_view_icon('delete'),
	'is_action' => true,
	'class' => 'elgg-admin-notice-dismiss float-alt',
	'is_trusted' => true,
]);

echo elgg_view_message('notice', $delete . $message, ['title' => false]);
