<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	$tag = get_input("tag");
	$filter = get_input("filter");
	if(!$filter)
		$filter = "newest";
	
	
	// Get objects
	$context = get_context();
	
	set_context('search');
	if ($tag != "")
		$objects = list_entities_from_metadata('tags',$tag,'group',"","", $limit, false);
	else{
		switch($filter){
			case "newest":
			$objects = list_entities('group',"", 0, $limit, false);
			break;
			case "pop":
			$objects = list_entities_by_relationship_count('member');
			break;
			case "active":
			$objects = list_entities_from_annotations("object", "groupforumtopic", "group_topic_post", "", 40, 0, 0, false, true);
			break;
			case 'default':
			$objects = list_entities('group',"", 0, $limit, false);
			break;
		}
	}
	
	//get a group count
	$group_count = get_entities("group", "", 0, "", 10, 0, true, 0, null);
		
	//find groups
	$area1 = elgg_view("groups/find");
	
	//menu options
	$area1 .= elgg_view("groups/side_menu");
	
	//featured groups
	$featured_groups = get_entities_from_metadata("featured_group", "yes", "group", "", 0, 10, false, false, false);	
	$area1 .= elgg_view("groups/featured", array("featured" => $featured_groups));
		
		
	set_context($context);
	
	$title = sprintf(elgg_echo("groups:all"),page_owner_entity()->name);
	$area2 = elgg_view_title($title);
	$area2 .= elgg_view('groups/contentwrapper', array('body' => elgg_view("groups/group_sort_menu", array("count" => $group_count, "filter" => $filter)) . $objects));
	$body = elgg_view_layout('sidebar_boxes',$area1, $area2);
	
	// Finally draw the page
	page_draw($title, $body);



?>