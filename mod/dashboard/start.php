<?php
/**
 * A user dashboard
 */

elgg_register_event_handler('init', 'system', 'dashboard_init');

function dashboard_init() {
	elgg_register_page_handler('dashboard', 'dashboard_page_handler');

	elgg_extend_view('css/elgg', 'dashboard/css');
	elgg_extend_view('js/elgg', 'dashboard/js');

	elgg_register_menu_item('topbar', array(
		'name' => 'dashboard',
		'href' => 'dashboard',
		'text' => elgg_view_icon('home') . elgg_echo('dashboard'),
		'priority' => 450,
		'section' => 'alt',
	));

	elgg_register_plugin_hook_handler('get_list', 'default_widgets', 'dashboard_default_widgets');
}

/**
 * Dashboard page handler
 * @return bool
 */
function dashboard_page_handler() {
	// Ensure that only logged-in users can see this page
	elgg_gatekeeper();

	// Set context and title
	elgg_set_context('dashboard');
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
	$title = elgg_echo('dashboard');

	// wrap intro message in a div
	$intro_message = elgg_view('dashboard/blurb');

	$params = array(
		'content' => $intro_message,
		'num_columns' => 3,
		'show_access' => false,
	);
	$widgets = elgg_view_layout('widgets', $params);

	$body = elgg_view_layout('one_column', array(
		'title' => false,
		'content' => $widgets
	));

	echo elgg_view_page($title, $body);
	return true;
}


/**
 * Register user dashboard with default widgets
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $return
 * @param unknown_type $params
 * @return array
 */
function dashboard_default_widgets($hook, $type, $return, $params) {
	$return[] = array(
		'name' => elgg_echo('dashboard'),
		'widget_context' => 'dashboard',
		'widget_columns' => 3,

		'event' => 'create',
		'entity_type' => 'user',
		'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
	);

	return $return;
}