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
	
	$title = sprintf(elgg_echo("groups:owned"),page_owner_entity()->name);

	// Get objects
	$area2 = elgg_view_title($title);
	
	set_context('search');
	$objects = elgg_list_entities(array('types' => 'group', 'owner_guid' => page_owner(), 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));
	set_context('groups');
	
	$area2 .= $objects;
	$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
	
	// Finally draw the page
	page_draw($title, $body);
?>