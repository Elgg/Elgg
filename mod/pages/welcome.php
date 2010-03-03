<?php
	/**
	 * Elgg Pages welcome intro
	 * The user or group owner can set an introduction to their wiki pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010 - 2009
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();
	global $CONFIG;
	
	// Get the current page's owner
		if ($container = (int) get_input('container_guid')) {
			set_page_owner($container);
		}
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
    
	//get the owners welcome message if it exists
	$welcome_message = elgg_get_entities(array('types' => 'object', 'subtypes' => 'pages_welcome', 'owner_guid' => $page_owner->getGUID(), 'limit' => 1));
	global $CONFIG;
	add_submenu_item(sprintf(elgg_echo("pages:user"), page_owner_entity()->name), $CONFIG->url . "pg/pages/owned/" . page_owner_entity()->username, 'pageslinksgeneral');
    
	$title = elgg_echo("pages:welcome");
	$area2 .= elgg_view_title($title);
	$area2 .= elgg_view("forms/pages/editwelcome", array('entity' => $welcome_message, 'owner' => $page_owner));
	
	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
	
	page_draw($title, $body);
?>