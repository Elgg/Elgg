<?php

if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	elgg_register_title_button('groups', 'add', 'group', 'group');
}

$selected_tab = get_input('filter', 'newest');

$content = groups_listing($selected_tab, $vars);

$sidebar = elgg_view('groups/sidebar/find');
$sidebar .= elgg_view('groups/sidebar/featured');

$title = elgg_echo('groups:all');

$body = elgg_view_layout('default', [
	'content' => $content,
	'sidebar' => $sidebar,
	'title' => $title,
	'filter_id' => 'groups/all',
	'filter_value' => $selected_tab,
]);

echo elgg_view_page($title, $body);
