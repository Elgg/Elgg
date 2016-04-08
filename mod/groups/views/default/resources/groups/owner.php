<?php

$page_owner = elgg_get_page_owner_entity();

if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('groups:owned');
} else {
	$title = elgg_echo('groups:owned:user', array($page_owner->name));
}
elgg_push_breadcrumb($title);

if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	elgg_register_title_button('groups', 'add', 'group');
}

$dbprefix = elgg_get_config('dbprefix');
$content = elgg_list_entities(array(
	'type' => 'group',
	'owner_guid' => elgg_get_page_owner_guid(),
	'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
	'order_by' => 'ge.name ASC',
	'full_view' => false,
	'no_results' => elgg_echo('groups:none'),
	'distinct' => false,
));

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);