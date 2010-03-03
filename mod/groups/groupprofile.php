<?php
	/**
	 * Full group profile
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	$group_guid = get_input('group_guid');
	set_context('groups');
	
	global $autofeed;
	$autofeed = true;
	
	$group = get_entity($group_guid);
	if ($group) {
		set_page_owner($group_guid);
		
		$title = $group->name;
		
		// Hide some items from closed groups when the user is not logged in.
		$view_all = true;
		
		$groupaccess = group_gatekeeper(false);
		if (!$groupaccess)
			$view_all = false;
		
		
		$area2 = elgg_view_title($title);
		$area2 .= elgg_view('group/group', array('entity' => $group, 'user' => $_SESSION['user'], 'full' => true));
		
		if ($view_all) {
			//group profile 'items' - these are not real widgets, just contents to display
			$area2 .= elgg_view('groups/profileitems',array('entity' => $group));
			
			//group members
			$area3 = elgg_view('groups/members',array('entity' => $group));
		}
		else
		{
			$area2 .= elgg_view('groups/closedmembership', array('entity' => $group, 'user' => $_SESSION['user'], 'full' => true));

		}
		
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2, $area3);
	} else {
		$title = elgg_echo('groups:notfound');
		
		$area2 = elgg_view_title($title);
		$area2 .= elgg_view('groups/contentwrapper',array('body' => elgg_echo('groups:notfound:details')));
		
		$body = elgg_view_layout('two_column_left_sidebar', "", $area2,"");
	}
		
	// Finally draw the page
	page_draw($title, $body);
?>