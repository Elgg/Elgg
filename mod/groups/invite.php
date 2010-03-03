<?php
	/**
	 * Invite users to groups
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
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
	
	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
	
	page_draw($title, $body);
?>