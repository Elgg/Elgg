<?php
/**
 * A persistent admin notice to be displayed on all admin pages until cleared.
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggAdminNotice) {
	return;
}

elgg_require_js('elgg/admin_notices');

$delete = elgg_view('output/url', [
	'href' => elgg_generate_action_url('entity/delete', ['guid' => $entity->guid]),
	'text' => false,
	'title' => elgg_echo('delete'),
	'icon' => 'delete',
	'class' => 'elgg-admin-notice-dismiss',
]);

echo elgg_view_message('notice', $entity->description, ['title' => false, 'link' => $delete]);
