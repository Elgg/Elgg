<?php
/**
 * Elgg latest content page
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Load the front page
global $CONFIG;

if(is_plugin_enabled('riverdashboard')) {
	$title = elgg_view_title(elgg_echo('content:latest'));
	set_context('search');
	$content = elgg_list_registered_entities(array('limit' => 10, 'full_view' => FALSE, 'allowed_types' => array('object','group')));
	set_context('latest');
} else {
	$content = "Riverdashboard not loaded";
}
$content = elgg_view_layout('one_column_with_sidebar', $title . $content);
page_draw(elgg_echo('content:latest'), $content);
