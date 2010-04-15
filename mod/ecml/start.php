<?php
/**
 * Provides the ECML service to plugins.
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 *
 * @todo
 *	Granular access to keywords based upon view.
 *	Update docs / help
 * 	Check for SQL injection problems.
 * 	Check entity keyword views against fullview.  Force to FALSE?
 */

/**
 * Init ECML
 */
function ecml_init() {
	require_once(dirname(__FILE__) . '/ecml_functions.php');
	global $CONFIG;

	define('ECML_ATTR_SEPARATOR', ' ');
	define('ECML_ATTR_OPERATOR', '=');

	// help page
	register_page_handler('ecml', 'ecml_help_page_handler');

	// admin access page
	register_page_handler('ecml_admin', 'ecml_admin_page_handler');
	register_elgg_event_handler('pagesetup', 'system', 'ecml_pagesetup');

	// CSS for admin access
	elgg_extend_view('css', 'ecml/admin/css');

	// admin action to save permissions
	register_action('ecml/save_permissions', FALSE, dirname(__FILE__) . '/actions/save_permissions.php', TRUE);

	// show ECML-enabled icon on free-text input areas
	elgg_extend_view('input/longtext',  'ecml/input_ext');
	elgg_extend_view('input/plaintext', 'ecml/input_ext');
	//elgg_extend_view('input/text', 'ecml/input_ext');

	// add parsing for core views.
	register_plugin_hook('get_views', 'ecml', 'ecml_views_hook');

	// get register the views we want to parse for ecml
	// @todo will need to do profiling to see if it would be faster
	// to foreach through this list and register to specific views or
	// do the check in a single plugin hook.
	// Wants array('view_name' => 'Short Description')
	$CONFIG->ecml_parse_views = trigger_plugin_hook('get_views', 'ecml', NULL, array());

	foreach ($CONFIG->ecml_parse_views as $view => $desc) {
		register_plugin_hook('view', $view, 'ecml_parse_view');
	}

	// provide a few built-in ecml keywords.
	// @todo could pull this out into an array here to save an API call.
	register_plugin_hook('get_keywords', 'ecml', 'ecml_keyword_hook');

	// grab the list of keywords and their views from plugins
	$CONFIG->ecml_keywords = trigger_plugin_hook('get_keywords', 'ecml', NULL, array());

	// grab permissions for specific views/contexts
	// this is a black list.
	// it's more efficient to use this as a blacklist
	// but probably makes more sense from a UI perspective as a whitelist.
	// uses [views][view_name] = array(keywords, not, allowed)
	$CONFIG->ecml_permissions = unserialize(get_plugin_setting('ecml_permissions', 'ecml'));
}

/**
 * Page setup. Adds admin controls to the admin panel for granular permission
 */
function ecml_pagesetup(){
	if (get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('ecml'), $CONFIG->wwwroot . 'pg/ecml_admin');
	}
}

/**
 * Display a help page for valid ECML keywords on this page.
 *
 * @param array $page
 */
function ecml_help_page_handler($page) {

	$content = elgg_view('ecml/help');
	echo page_draw(elgg_echo('ecml:help'), $content);
}

/**
 * Display a admin area for ECML
 *
 * @param array $page
 */
function ecml_admin_page_handler($page) {
	admin_gatekeeper();
	set_context('admin');
	$content = elgg_view('ecml/admin/ecml_admin');
	$body = elgg_view_layout('one_column_with_sidebar', $content);
	echo page_draw(elgg_echo('ecml:admin'), $body);
}

/**
 * Parses a registered view / context for supported keywords.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 * @return string
 */
function ecml_parse_view($hook, $entity_type, $return_value, $params) {
	global $CONFIG;

	// give me everything that is not a ], possibly followed by a :, and surrounded by [[ ]]s
	//$keyword_regex = '/\[\[([a-z0-9_]+):?([^\]]+)?\]\]/';
	$keyword_regex = '/\[\[([a-z0-9]+)([^\]]+)?\]\]/';
	$CONFIG->ecml_current_view = $params['view'];
	$return_value = preg_replace_callback($keyword_regex, 'ecml_parse_view_match', $return_value);

	return $return_value;
}


/**
 * Register default keywords.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function ecml_keyword_hook($hook, $type, $value, $params) {
	// I keep going back and forth about entity and view. They're powerful, but
	// a great way to let a site get hacked if the admin doesn't lock them down.
	$keywords = array('entity', 'view', 'youtube', 'slideshare', 'vimeo', 'googlemaps');

	foreach ($keywords as $keyword) {
		$value[$keyword] = array(
			'view' => "ecml/keywords/$keyword",
			'description' => elgg_echo("ecml:keywords:desc:$keyword"),
			'usage' => elgg_echo("ecml:keywords:usage:$keyword")
		);
	}

	return $value;
}

/**
 * Register default views to parse
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 */
function ecml_views_hook($hook, $type, $value, $params) {
	$value['annotation/generic_comment'] = elgg_echo('ecml:views:annotation_generic_comment');

	return $value;
}

register_elgg_event_handler('init', 'system', 'ecml_init');