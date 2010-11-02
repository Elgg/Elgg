<?php
/**
 * Elgg river dashboard plugin
 *
 * @package RiverDashboard
 */

function riverdashboard_init() {
	global $CONFIG;
	// Register and optionally replace the dashboard
	register_page_handler('dashboard', 'riverdashboard_page_handler');
	add_menu(elgg_echo('activity'), "pg/activity/");

	// Page handler
	register_page_handler('activity', 'riverdashboard_page_handler');
	elgg_extend_view('css', 'riverdashboard/css');

	// add an activity stream ECML keyword
	// we'll restrict it to use in sitepages's custom_frontpage
	register_plugin_hook('get_keywords', 'ecml', 'riverdashboard_ecml_keywords_hook');

	register_plugin_hook('get_views', 'ecml', 'riverdashboard_ecml_views_hook');
}

/**
 * Page handler for riverdash
 *
 * @param unknown_type $page
 */
function riverdashboard_page_handler($page){
	include(dirname(__FILE__) . "/index.php");
	return TRUE;
}

/**
 * For users to make a comment on a river item
 *
 * @param ElggEntity $entity The entity to comment on
 * @return string|false The HTML (etc) for the comment form, or false on failure
 */
function elgg_make_river_comment($entity){
	if (!($entity instanceof ElggEntity)) {
		return FALSE;
	} else {
		//display the comment form
		$comments = elgg_view('riverdashboard/rivercomment', array('entity' => $entity));
		return $comments;
	}
}


/**
 * Register activity keyword.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function riverdashboard_ecml_keywords_hook($hook, $type, $value, $params) {
	$value['activity'] = array(
		'view' => "riverdashboard/ecml/activity",
		'description' => elgg_echo('riverdashboard:ecml:desc:activity'),
		'usage' => elgg_echo('riverdashboard:ecml:usage:activity'),
		'restricted' => array('sitepages/custom_frontpage')
	);

	return $value;
}

/**
 * Register the activity front page with ECML.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function riverdashboard_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['riverdashboard/container'] = elgg_echo('riverdashboard:ecml:riverdashboard');

	return $return_value;
}

register_elgg_event_handler('init', 'system', 'riverdashboard_init');
