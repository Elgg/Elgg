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
	register_action('reportedcontent/add', FALSE, "{$CONFIG->pluginspath}reportedcontent/actions/add.php");
	register_action('reportedcontent/delete', FALSE, "{$CONFIG->pluginspath}reportedcontent/actions/delete.php");
	register_action('reportedcontent/archive', FALSE, "{$CONFIG->pluginspath}reportedcontent/actions/archive.php");
}

// Initialise Reported Content
register_elgg_event_handler('init','system','reportedcontent_init');
