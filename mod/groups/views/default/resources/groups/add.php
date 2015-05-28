<?php

elgg_gatekeeper();

elgg_require_js('elgg/groups/edit');

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
$title = elgg_echo('groups:add');
elgg_push_breadcrumb($title);
if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	$content = elgg_view('groups/edit');
} else {
	$content = elgg_echo('groups:cantcreate');
}

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);