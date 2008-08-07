<?php
	/**
	 * Elgg groups forum
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	set_page_owner((int)get_input('group_guid'));
	if (!(page_owner_entity() instanceof ElggGroup)) forward();
	
	//get any forum topics
	$topics = get_entities("object", "groupforumtopic", 0, "", 50, 0, false, 0, get_input('group_guid'));
		
	$area2 = elgg_view("forum/topics", array('entity' => $topics));
	
	$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
	
	// Finally draw the page
	page_draw($title, $body);



?>