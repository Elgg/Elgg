<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	group_gatekeeper();

	$group_guid = get_input('group_guid');
	$group = get_entity($group_guid);
	set_page_owner($group_guid);

	$title = elgg_echo("groups:edit");
	$body = elgg_view_title($title);
	
	if (($group) && ($group->canEdit()))
	{
		$body .= elgg_view("forms/groups/edit", array('entity' => $group));
			 
	} else {
		$body .= elgg_echo('groups:noaccess');
	}
	
	$body = elgg_view_layout('one_column_with_sidebar', array('content' => $body));
	
	echo elgg_view_page($title, $body);
?>