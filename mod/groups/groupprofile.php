<?php
	/**
	 * Full group profile
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$group_guid = get_input('group_guid');
	set_context('groups');
	
	
	$group = get_entity($group_guid);
	
	set_page_owner($group_guid);
	
	$area2 = elgg_view_title($group->name);
	$area2 .= elgg_view('group/group', array('entity' => $group, 'user' => $_SESSION['user'], 'full' => true));
	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
	
	// Finally draw the page
	page_draw($group->name, $body);
?>