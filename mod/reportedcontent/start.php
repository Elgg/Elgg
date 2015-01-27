<?php
/**
 * Elgg Reported content.
 *
 * @package ElggReportedContent
 */

elgg_register_event_handler('init', 'system', 'reportedcontent_init');

/**
 * Initialize the plugin
 */
function reportedcontent_init() {

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('reportedcontent', 'reportedcontent_page_handler');
	
	// Extend CSS
	elgg_extend_view('css/elgg', 'reportedcontent/css');
	elgg_extend_view('css/admin', 'reportedcontent/admin_css');


	if (elgg_is_logged_in()) {
		elgg_require_js('elgg/reportedcontent');

		// Extend footer with report content link
		elgg_register_menu_item('extras', array(
			'name' => 'report_this',
			'href' => 'reportedcontent/add',
			'title' => elgg_echo('reportedcontent:this:tooltip'),
			'text' => elgg_view_icon('report-this'),
			'priority' => 500,
			'section' => 'default',
			'link_class' => 'elgg-lightbox',
		));
	}

	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'reportedcontent_user_hover_menu');

	// Add admin menu item
	// @todo Might want to move this to a 'feedback' section. something other than utils
	elgg_register_admin_menu_item('administer', 'reportedcontent', 'administer_utilities');

	elgg_register_widget_type(
			'reportedcontent',
			elgg_echo('reportedcontent'),
			elgg_echo('reportedcontent:widget:description'),
			array('admin'));

	// Register actions
	$action_path = elgg_get_plugins_path() . "reportedcontent/actions/reportedcontent";
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
 * @return bool
 */
function reportedcontent_page_handler($page) {
	// only logged in users can report things
	elgg_gatekeeper();

	if (elgg_extract(0, $page) === 'add' && elgg_is_xhr()) {
		echo elgg_view('resources/reportedcontent/add_form');
		return true;
	}

	$title = elgg_echo('reportedcontent:this');
	
	$content = elgg_view_form('reportedcontent/add');
	$sidebar = elgg_echo('reportedcontent:instructions');

	$params = array(
		'title' => $title,
		'content' => $content,
		'sidebar' => $sidebar,
	);
	$body = elgg_view_layout('one_sidebar', $params);

	echo elgg_view_page($title, $body);
	return true;
}

/**
 * Add report user link to hover menu
 */
function reportedcontent_user_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];
	/* @var ElggUser $user */

	$profile_url = urlencode($user->getURL());
	$name = urlencode($user->name);
	$url = "reportedcontent/add?address=$profile_url&title=$name";

	if (elgg_is_logged_in() && elgg_get_logged_in_user_guid() != $user->guid) {
		$item = new ElggMenuItem(
				'reportuser',
				elgg_echo('reportedcontent:user'),
				$url);
		$item->setSection('action');
		$item->addLinkClass('elgg-lightbox');
		$return[] = $item;
	}

	return $return;
}