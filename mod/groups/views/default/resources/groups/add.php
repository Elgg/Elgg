<?php

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");

elgg_require_js('elgg/groups/edit');

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
$title = elgg_echo('groups:add');

if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	$content = elgg_view('groups/edit');
} else {
	$content = elgg_echo('groups:cantcreate');
}

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
]);

echo elgg_view_page($title, $body);
