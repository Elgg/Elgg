<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();

	$group_guid = get_input('group_guid');
	$group = get_entity($group_guid);
	set_page_owner($group_guid);

	$title = elgg_echo("groups:edit");
	$body = elgg_view_title($title);
	
	if (($group) && ($group->canEdit()))
	{
		$body .= elgg_view("forms/groups/edit", array('entity' => $group));
			 
	} else {
		$body .= elgg_view('groups/contentwrapper',array('body' => elgg_echo('groups:noaccess')));
	}
	
	$body = elgg_view_layout('two_column_left_sidebar', '', $body);
	
	page_draw($title, $body);
?>