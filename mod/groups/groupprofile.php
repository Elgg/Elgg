<?php
	/**
	 * Full group profile
	 * 
	 * @package ElggGroups
	 */

	$group_guid = get_input('group_guid');
	elgg_set_context('groups');
	
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
		
		$area2 .= elgg_view_entity($group, TRUE);
		
		if ($view_all) {
			//group profile 'items' - these are not real widgets, just contents to display
			$area2 .= elgg_view('groups/profileitems',array('entity' => $group));
			
			//group members
			$area3 = elgg_view('groups/members',array('entity' => $group));
		} else {
			$area2 .= elgg_view('groups/closedmembership', array('entity' => $group, 'user' => get_loggedin_user(), 'full' => true));
		}

		$content = $area1 . $area2;
		$params = array(
			'content' => $content,
			'sidebar' => $area3
		);
		$body = elgg_view_layout('one_column_with_sidebar', $params);
	} else {
		$title = elgg_echo('groups:notfound');
		
		$area2 = elgg_view_title($title);
		$area2 .= "<p class='margin_top'>".elgg_echo('groups:notfound:details')."</p>";
		
		$body = elgg_view_layout('one_column_with_sidebar', array('content' => $area2));
	}
		
	// Finally draw the page
	echo elgg_view_page($title, $body);
?>