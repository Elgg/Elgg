<?php
	/**
	 * Manage group invite requests.
	 * 
	 * @package ElggGroups
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();
	
	$group_guid = (int) get_input('group_guid');
	$group = get_entity($group_guid);
	set_page_owner($group_guid);

	$title = elgg_echo('groups:membershiprequests');

	$area2 = elgg_view_title($title);
	
	if (($group) && ($group->canEdit()))
	{	
		
		$requests = elgg_get_entities_from_relationship(array('relationship' => 'membership_request', 'relationship_guid' => $group_guid, 'inverse_relationship' => TRUE, 'limit' => 9999));
		$area2 .= elgg_view('groups/membershiprequests',array('requests' => $requests, 'entity' => $group));
			 
	} else {
		$area2 .= elgg_echo("groups:noaccess");
	}
	
	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
	
	page_draw($title, $body);
?>