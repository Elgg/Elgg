<?php
	/**
	 * Full group profile
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$group_guid = get_input('group_guid');
	set_context('groups');
	
	
	$group = get_entity($group_guid);
	
	set_page_owner($group_guid);
	
	$area2 = elgg_view_title($group->name);
	$area2 .= elgg_view('group/group', array('entity' => $group, 'user' => $_SESSION['user'], 'full' => true));
	
	//group profile 'items' - these are not real widgets, just contents to display
	$area2 .= elgg_view('groups/profileitems',array('entity' => $group));
	
	//group members
	$area3 = elgg_view('groups/members',array('entity' => $group));
	
	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2, $area3);
	
	// Finally draw the page
	page_draw($group->name, $body);
?>