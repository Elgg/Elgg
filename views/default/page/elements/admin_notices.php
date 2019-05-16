<?php
/**
 * Lists admin notices
 *
 * @uses $vars['notices'] Array of ElggObject notices
 */

if (!elgg_is_admin_logged_in() || !elgg_in_context('admin')) {
	return;
}

$count = elgg_get_admin_notices([
	'count' => true,
]);

if (!$count) {
	return;
}

$button = '';
if ($count > 5) {
	$button = elgg_view('output/url', [
		'class' => 'elgg-admin-notices-dismiss-all',
		'text' => elgg_echo('admin:notices:delete_all', [$count]),
		'href' => 'action/admin/delete_admin_notices',
		'is_action' => true,
		'confirm' => true,
		'icon' => 'times',
	]);
}

$notices = elgg_get_admin_notices([
	'limit' => 5,
]);

$list = elgg_view_entity_list($notices, [
	'list_class' => 'elgg-admin-notices',
	'register_rss_link' => false,
]);

echo elgg_view_module('admin-notices', ' ', $list, [
	'menu' => $button,
]);
