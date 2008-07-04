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
	
	
	$body = elgg_view_layout('one_column', elgg_view('group/group', array('entity' => $group, 'user' => $_SESSION['user'], 'full' => true)));
	
	$body .= elgg_view_layout('widgets', "", "", elgg_echo('groups:nowidgets'));
	
	// Finally draw the page
	page_draw($group->name, $body);
?>