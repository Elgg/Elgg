<?php

/**
 * Resource view for groups/all route
 *
 * @tip To add a new tab:
 * 1) Use 'filter_tabs','groups/all' hook to add a new filter tab item
 * 2) Add a tab content view to groups/listing/<tab_name>
 */
// all groups doesn't get link to self
elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('groups'));

if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	elgg_register_title_button('groups', 'add', 'group');
}

$selected_tab = get_input('filter', 'newest');

$plugin_views_path = dirname(dirname(dirname(__FILE__)));
if (_elgg_view_may_be_altered('groups/group_sort_menu', "$plugin_views_path/groups/group_sort_menu.php")) {
	elgg_deprecated_notice("'groups/group_sort_menu' view has been deprecated. "
		. "Use elgg_get_filter_tabs() as the layout filter value.", '2.3');

	// Unknown tab
	if (!elgg_view_exists("groups/listing/$selected_tab")) {
		$selected_tab = 'newest';
	}

	// We need to make sure that tabs added via the hook appear in the filter menu
	$filter_vars = ['__ignore_defaults' => true]; // used to ensure BC in the hook
	$tabs = elgg_get_filter_tabs('groups/all', $selected_tab, [], $filter_vars);
	foreach ($tabs as $name => $tab) {
		elgg_register_menu_item('filter', $tab);
	}

	$filter = elgg_view('groups/group_sort_menu', [
		'selected' => $selected_tab,
	]);
} else {
	$tabs = elgg_get_filter_tabs('groups/all', $selected_tab);
	if (!array_key_exists($selected_tab, $filter)) {
		// use the first tab
		$selected_tab = array_values($tabs)[0]['name'];
	}
}

$content_view = "groups/listing/$selected_tab";
$content = elgg_view($content_view, $vars);

$sidebar = elgg_view('groups/sidebar/find');
$sidebar .= elgg_view('groups/sidebar/featured');

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => $filter,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo('groups:all'), $body);
