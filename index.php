<?php
/**
 * Elgg index page for web-based applications
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 * Start the Elgg engine
 */
define('externalpage', TRUE);
require_once(dirname(__FILE__) . "/engine/start.php");

if (!trigger_plugin_hook('index', 'system', null, FALSE)) {
	if (isloggedin()) {
		forward('pg/dashboard/');
	}

	/*
	River dashboard should respond to the index:system plugin hook instead of
	being hard-coded here.
	if(is_plugin_enabled('riverdashboard')){
		$title = elgg_view_title(elgg_echo('content:latest'));
		set_context('search');
		$content = elgg_list_registered_entities(array('limit' => 10, 'full_view' => FALSE, 'allowed_types' => array('object','group')));
		set_context('main');
	}
	*/
	
	global $autofeed;
	$autofeed = FALSE;
	$content = elgg_view_layout('one_column_with_sidebar', elgg_view('account/forms/login'), $title . $content);
	page_draw(null, $content);
}
