<?php
/**
 * A persistent admin notice to be displayed on all admin pages until cleared.
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggAdminNotice) {
	return;
}

elgg_require_js('elgg/admin_notices');

$message = $entity->description;

$delete = elgg_view('output/url', [
	'href' => elgg_generate_action_url('entity/delete', ['guid' => $entity->guid]),
	'text' => elgg_view_icon('delete'),
	'class' => 'elgg-admin-notice-dismiss float-alt',
	'is_trusted' => true,
]);

echo elgg_view_message('notice', $delete . $message, ['title' => false]);
