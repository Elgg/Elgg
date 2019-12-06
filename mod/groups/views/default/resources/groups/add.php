<?php

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");

elgg_require_js('elgg/groups/edit');

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	$content = elgg_view('groups/edit');
} else {
	$content = elgg_echo('groups:cantcreate');
}

echo elgg_view_page(elgg_echo('groups:add'), [
	'content' => $content,
]);
