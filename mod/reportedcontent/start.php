<?php
/**
 * Elgg Reported content.
 *
 * @package ElggReportedContent
 */

elgg_register_event_handler('init','system','reportedcontent_init');

/**
 * Initialize the plugin
 */
function reportedcontent_init() {

	// Register a page handler, so we can have nice URLs
	register_page_handler('reportedcontent', 'reportedcontent_page_handler');
	
	// Extend CSS
	elgg_extend_view('css/screen', 'reportedcontent/css');
	elgg_extend_view('css/admin', 'reportedcontent/admin_css');

	// Extend context menu and footer with report content link
	if (isloggedin()) {
		elgg_extend_view('profile/menu/links', 'reportedcontent/user_report');
		elgg_extend_view('footer/links', 'reportedcontent/footer_link');
	}

	// Add admin menu item
	elgg_add_admin_submenu_item('reportedcontent', elgg_echo('reportedcontent'), 'overview');

	// Register actions
	$action_path = elgg_get_plugin_path() . "reportedcontent/actions";
	elgg_register_action('reportedcontent/add', "$action_path/add.php");
	elgg_register_action('reportedcontent/delete', "$action_path/delete.php", 'admin');
	elgg_register_action('reportedcontent/archive', "$action_path/archive.php", 'admin');
}

/**
 * Reported content page handler
 *
 * Serves the add report page
 *
 * @param array $page Array of page routing elements
 */
function reportedcontent_page_handler($page) {
	// only logged in users can report things
	gatekeeper();

	$content .= elgg_view_title(elgg_echo('reportedcontent:this'));
	$content .= elgg_view('reportedcontent/form');
	$sidebar .= elgg_echo('reportedcontent:instructions');

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar
	);
	$body = elgg_view_layout('one_column_with_sidebar', $params);

	echo elgg_view_page(elgg_echo('reportedcontent:this'), $body);
}
