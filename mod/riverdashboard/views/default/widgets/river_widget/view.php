<?php
	/**
	 * View the widget
	 * 
	 * @package ElggRiver
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$owner = page_owner_entity();
	
	//get the type - mine or friends
	$type = $vars['entity']->content_type;
	if(!$type)
		$type = "mine";
		
	//based on type grab the correct content type
	if($type == "mine")
		$content_type = '';
	else
		$content_type = 'friend';
		
	//get the number of items to display
	$limit = $vars['entity']->num_display;
	if(!$limit)
		$limit = 4;
	
	//grab the river
	$river = elgg_view_river_items($owner->getGuid(), 0, $content_type, $content[0], $content[1], '', $limit,0,0,false);
	
	//display
	echo "<div class=\"contentWrapper\">";
	if($type != 'mine')
		echo "<div class='content_area_user_title'><h2>" . elgg_echo("friends") . "</h2></div>";
	echo $river;
	echo "</div>";
	
?>