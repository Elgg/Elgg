<?php
/**
 * Elgg members index page
 *
 * @package ElggMembers
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the current page's owner
$page_owner = elgg_get_page_owner();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = get_loggedin_user();
	//set_page_owner($page_owner->getGUID());
}

// get filter parameters
$limit = get_input('limit', 10);
$offset = get_input('offset', 0);
$filter = get_input("filter", "newest");

// search options
$tag = get_input('tag');

// friends links
$area1 = "<ul class='submenu page_navigation'><li><a href=\"" . elgg_get_site_url()."pg/friends/" . elgg_get_page_owner()->username . "\">". elgg_echo('friends') . "</a></li>";
$area1 .= "<li><a href=\"" . elgg_get_site_url()."pg/friendsof/" . elgg_get_page_owner()->username . "\">". elgg_echo('friends:of') . "</a></li>";
$area1 .= "<li class='selected'><a href=\"" . elgg_get_site_url()."mod/members/index.php\">". elgg_echo('members:browse') . "</a></li>";
$area1 .= "</ul>";

//search members
$area1 .= elgg_view("members/search");

// count members
$members = get_number_users();

// title
$pagetitle = elgg_echo("members:members")." ({$members})";
$area2 = elgg_view_title($pagetitle);

//get the correct view based on filter
switch($filter){
	case "newest":
	$filter_content = elgg_list_entities(array('type' => 'user', 'offset' => $offset, 'full_view' => FALSE));
	break;
	case "pop":
		$filter_content = list_entities_by_relationship_count('friend', true, '', '', 0, 10, false);
		break;
	case "active":
		$filter_content = get_online_users();
		break;
	// search based on name
	case "search":
		elgg_set_context('search');
		$filter_content = list_user_search($tag);
		break;
	// search based on tags
	case "search_tags":
		$options = array();
		$options['query'] = $tag;
		$options['type'] = "user";
		$options['offset'] = $offset;
		$options['limit'] = $limit;
		$results = elgg_trigger_plugin_hook('search', 'tags', $options, array());
		$count = $results['count'];
		$users = $results['entities'];
		$filter_content = elgg_view_entity_list($users, $count, $offset, $limit, false, false, true);
		break;
	case 'default':
		$filter_content = elgg_list_entities(array('type' => 'user', 'offset' => $offset, 'full_view' => FALSE));
		break;
}

$area2 .= elgg_view('page_elements/content', array('body' => elgg_view("members/members_navigation", array("count" => $members, "filter" => $filter)) . "<div class='members_list'>".$filter_content."</div>", 'subclass' => 'members'));

//select the correct canvas area
$params = array(
	'content' => $area2,
	'sidebar' => $area1
);
$body = elgg_view_layout("one_column_with_sidebar", $params);

// Display page
echo elgg_view_page(elgg_echo('members:members', array($page_owner->name)), $body);