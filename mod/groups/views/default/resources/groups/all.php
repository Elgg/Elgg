<?php

// all groups doesn't get link to self
elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('groups'));

if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	elgg_register_title_button('groups', 'add', 'group');
}

$selected_tab = get_input('filter', 'newest');
if (!elgg_view_exists("groups/listing/$selected_tab")) {
	$selected_tab = 'newest';
}

$content = elgg_view("groups/listing/$selected_tab", $vars);

$filter = elgg_view('groups/group_sort_menu', array('selected' => $selected_tab));

$sidebar = elgg_view('groups/sidebar/find');
$sidebar .= elgg_view('groups/sidebar/featured');

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => $filter,
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo('groups:all'), $body);