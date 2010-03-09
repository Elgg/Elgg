<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	$title = elgg_echo("groups:owned");

	// Get objects
	$area2 = elgg_view('page_elements/content_header', array('context' => "everyone", 'type' => 'groups'));
	
	set_context('search');
	$objects = elgg_list_entities(array('types' => 'group', 'owner_guid' => page_owner(), 'limit' => $limit, 'full_view' => FALSE));
	set_context('groups');
	
	$area2 .= $objects;
	$body = elgg_view_layout('one_column_with_sidebar', $area1.$area2);
	
	// Finally draw the page
	page_draw($title, $body);
?>