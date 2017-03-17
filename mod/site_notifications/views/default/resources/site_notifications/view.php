<?php
/**
 * View a user's site notifications
 */
elgg_gatekeeper();
elgg_load_js('elgg.site_notifications');

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner || !$page_owner->canEdit()) {
	// must have access to view
	register_error(elgg_echo('site_notifications:no_access'));
	forward();
}

elgg_push_breadcrumb(elgg_echo('site_notifications'), 'site_notifications');
elgg_push_breadcrumb($page_owner->name);

$title = elgg_echo('site_notifications');

$list = elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => 'site_notification',
	'owner_guid' => $page_owner->guid,
	'full_view' => false,
	'metadata_name' => 'read',
	'metadata_value' => false,
]);

$body_vars = [
	'list' => $list
];

$form = elgg_view_form("site_notifications/process", [], $body_vars);

$body = elgg_view_layout('content', [
	'content' => $form,
	'title' => $title,
	'filter' => '',
]);

echo elgg_view_page($title, $body);
