<?php
	/**
	 * Invite users to groups
	 * 
	 * @package ElggGroups
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();
	
	$group_guid = (int) get_input('group_guid');
	$group = get_entity($group_guid);
	set_page_owner($group_guid);

	$title = elgg_echo("groups:invite");

	$area2 = elgg_view_title($title);
	
	if (($group) && ($group->canEdit()))
	{	
		$area2 .= elgg_view("forms/groups/invite", array('entity' => $group));
			 
	} else {
		$area2 .= elgg_echo("groups:noaccess");
	}
	
	$body = elgg_view_layout('one_column_with_sidebar', array('content' => $area1 . $area2));
	
	echo elgg_view_page($title, $body);
?>