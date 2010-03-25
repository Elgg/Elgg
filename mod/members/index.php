<?php

/**
 * Elgg members index page - called from filter or search
 * 
 * @package ElggMembers
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// get filter parameters
$limit = get_input('limit', 10);
$offset = get_input('offset', 0);
$filter = get_input("filter", "newest");

// search options
$tag = get_input('tag');


//search members
$sidebar = elgg_view("members/search");
	    
// get the correct content based on filter/search
switch ($filter) {
	case "pop":
		$filter_content = list_entities_by_relationship_count('friend', true, '', '', 0, 10, false);
	break;
	case "active":
		$filter_content = elgg_view("members/online");
	break;
	// search based on name
	case "search":
		set_context('search');
		$filter_content = list_user_search($tag);
	break;
	// search based on tags
	case "search_tags":
		$options = array();
		$options['query'] = $tag;
		$options['type'] = "user";
		$options['offset'] = $offset;
		$options['limit'] = $limit;
		$results = trigger_plugin_hook('search', 'tags', $options, array());
		$count = $results['count'];
		$users = $results['entities'];
		$filter_content = elgg_view_entity_list($users, $count, $offset, $limit, false, false, true);
	break;
	case "newest":
	case 'default':
		$filter_content = elgg_list_entities(array('type' => 'user', 'offset' => $offset, 'full_view' => FALSE));
	break;
}

// create the members navigation/filtering
$members = get_number_users();
$members_nav = elgg_view("members/members_sort_menu", array("count" => $members, "filter" => $filter));

$content = $members_nav . $filter_content;

// title
$main_content = elgg_view_title(elgg_echo("members:members"));

$main_content .= elgg_view('page_elements/contentwrapper', array('body' => $content, 'subclass' => 'members'));

$body = elgg_view_layout("sidebar_boxes", $sidebar, $main_content);

page_draw(elgg_echo('members:members'), $body);
