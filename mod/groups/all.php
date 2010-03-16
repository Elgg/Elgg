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
	$tag = get_input("tag");
	$filter = get_input("filter");
	if (!$filter) {
		// active discussions is the default
		$filter = "active";
	}
	
	
	// Get objects
	$context = get_context();
	
	set_context('search');
	if ($tag != "") {
		$filter = 'search';
		// groups plugin saves tags as "interests" - see groups_fields_setup() in start.php
		$objects = list_entities_from_metadata('interests',$tag,'group',"","", $limit, false, false, true, false);
	} else {
		switch($filter){
			case "newest":
			$objects = elgg_list_entities(array('types' => 'group', 'owner_guid' => 0, 'limit' => $limit, 'offset' => $offset, 'full_view' => false));
			break;
			case "pop":
			$objects = list_entities_by_relationship_count('member', true, "", "", 0, $limit, false);
			break;
			case "active":
			case 'default':
			$objects = list_entities_from_annotations("object", "groupforumtopic", "group_topic_post", "", 40, 0, 0, false, true);
			break;
		}
	}
	
	//get a group count
	$group_count = elgg_get_entities(array('types' => 'group', 'limit' => 10, 'count' => TRUE));
		
	//find groups
	$area1 = elgg_view("groups/find");
	
	//menu options
	$area1 .= elgg_view("groups/side_menu");
	
	//featured groups
	$featured_groups = elgg_get_entities_from_metadata(array('metadata_name' => 'featured_group', 'metadata_value' => 'yes', 'types' => 'group', 'limit' => 10));
	$area1 .= elgg_view("groups/featured", array("featured" => $featured_groups));
		
		
	set_context($context);
	
	$title = sprintf(elgg_echo("groups:all"),page_owner_entity()->name);
	$area2 = elgg_view_title($title);
	$area2 .= elgg_view('groups/contentwrapper', array('body' => elgg_view("groups/group_sort_menu", array("count" => $group_count, "filter" => $filter)) . $objects));
	$body = elgg_view_layout('sidebar_boxes',$area1, $area2);
	
	// Finally draw the page
	page_draw($title, $body);



?>