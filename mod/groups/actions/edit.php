<?php
	/**
	 * Elgg groups plugin edit action.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;

	// Get group fields
	$input = array();
	foreach($CONFIG->group as $shortname => $valuetype) {
		$input[$shortname] = get_input($shortname);
		if ($valuetype == 'tags')
			$input[$shortname] = string_to_tag_array($input[$shortname]);
	}
	
	$user_guid = get_input('user_guid');
	$user = NULL;
	if (!$user_guid) $user = $_SESSION['user'];
	else
		$user = get_entity($user_guid);
		
	$group_guid = get_input('group_guid');
	
	$group = new ElggGroup($group_guid); // load if present, if not create a new group
	if (($group_guid) && (!$group->canEdit()))
	{
		system_message(elgg_echo("groups:cantedit"));
		
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	// Assume we can edit or this is a new group
	if (sizeof($input) > 0)
	{
		foreach($input as $shortname => $value) {
			$group->$shortname = $value;
		}
	}
	
	// Get access
	$group->access_id = get_input('access_id', 0);
	
	$group->save();
	
	$group->join($user); // Creator always a member
	
	system_message(elgg_echo("groups:saved"));
	
	// Forward to the user's profile
	forward($group->getUrl());
	exit;
?>