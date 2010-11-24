<?php
/**
 * Elgg Reported content.
 *
 * @package ElggReportedContent
 */

/**
 * Initialise the Reported content and set up the menus.
 *
 */
function reportedcontent_init() {
	global $CONFIG;

	// Extend CSS
	elgg_extend_view('css', 'reportedcontent/css');

	// Extend context menu and footer with report content link
	if (isloggedin()) {
		elgg_extend_view('profile/menu/links', 'reportedcontent/user_report');
		elgg_extend_view('footer/links', 'reportedcontent/footer_link');
	}

	elgg_add_admin_submenu_item('reportedcontent', elgg_echo('reportedcontent'), 'overview');

	//register action
	elgg_register_action('reportedcontent/add', "{$CONFIG->pluginspath}reportedcontent/actions/add.php");
	elgg_register_action('reportedcontent/delete', "{$CONFIG->pluginspath}reportedcontent/actions/delete.php", 'admin');
	elgg_register_action('reportedcontent/archive', "{$CONFIG->pluginspath}reportedcontent/actions/archive.php", 'admin');
}

// Initialise Reported Content
elgg_register_event_handler('init','system','reportedcontent_init');
