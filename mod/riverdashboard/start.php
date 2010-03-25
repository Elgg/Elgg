<?php
/**
 * Elgg river dashboard plugin
 */

function riverdashboard_init() {
	global $CONFIG;
	// Register and optionally replace the dashboard
	if (get_plugin_setting('useasdashboard', 'riverdashboard') == 'yes') {
		register_page_handler('dashboard','riverdashboard_page_handler');
	} else {
		// Activity main menu
		add_menu(elgg_echo('activity'), $CONFIG->wwwroot . "mod/riverdashboard/");
	}	
	// Page handler
	register_page_handler('riverdashboard','riverdashboard_page_handler');
	elgg_extend_view('css','riverdashboard/css');
}
		
/**
 * Page handler for riverdash
 *
 * @param unknown_type $page
 */
function riverdashboard_page_handler($page){
	global $CONFIG;
	include(dirname(__FILE__) . "/index.php");
	return true;
}
		
function riverdashboard_dashboard() {
	include(dirname(__FILE__) . '/index.php');
}

/**
 * For users to make a comment on a river item
 *
 * @param ElggEntity $entity The entity to comment on
 * @return string|false The HTML (etc) for the comment form, or false on failure
 */
function elgg_make_river_comment($entity){
	if (!($entity instanceof ElggEntity)) {
		return false;
	} else {
		//display the comment form
		$comments = elgg_view('riverdashboard/rivercomment',array('entity' => $entity));
		return $comments;
	}
}

register_elgg_event_handler('init','system','riverdashboard_init');
