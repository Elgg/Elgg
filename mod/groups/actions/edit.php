<?php
	/**
	 * Elgg groups plugin edit action.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;
	
	// Get group fields
	$input = array();
	foreach($CONFIG->group as $shortname => $valuetype) {
		$input[$shortname] = get_input($shortname);
		if ($shortname == 'name') {
			$input[$shortname] = strip_tags($input[$shortname]);
		}
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
		register_error(elgg_echo("groups:cantedit"));
		
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
	
	// Validate create
	if (!$group->name)
	{
		register_error(elgg_echo("groups:notitle"));
		
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	// Group membership - should these be treated with same constants as access permissions?
	switch (get_input('membership'))
	{
		case ACCESS_PUBLIC: 
			$group->membership = ACCESS_PUBLIC; 
			break;
		default: 
			$group->membership = ACCESS_PRIVATE; 
	}
	
	// Set access - all groups are public from elgg's point of view, unless the override is in place

	if (get_plugin_setting('hidden_groups', 'groups') == 'yes')
	{
		$visibility = (int)get_input('vis','',false);
		
		$group->access_id = $visibility;
	}
	else
		$group->access_id = ACCESS_PUBLIC;
	
	// Set group tool options
	//$group->files_enable = get_input('files_enable', 'yes');
	//$group->pages_enable = get_input('pages_enable', 'yes');
	//$group->forum_enable = get_input('forum_enable', 'yes');
	
	// Set group tool options
	if (isset($CONFIG->group_tool_options)) {
		foreach($CONFIG->group_tool_options as $group_option) {
			$group_option_toggle_name = $group_option->name."_enable";
			if ($group_option->default_on) {
				$group_option_default_value = 'yes';
			} else {
				$group_option_default_value = 'no';
			}
			$group->$group_option_toggle_name = get_input($group_option_toggle_name, $group_option_default_value);
		}
	}	

	$group->save();
	
	if (!$group->isMember($user))
		$group->join($user); // Creator always a member
	
	
	// Now see if we have a file icon
	if ((isset($_FILES['icon'])) && (substr_count($_FILES['icon']['type'],'image/')))
	{
		$prefix = "groups/".$group->guid;
		
		$filehandler = new ElggFile();
		$filehandler->owner_guid = $group->owner_guid;
		$filehandler->setFilename($prefix . ".jpg");
		$filehandler->open("write");
		$filehandler->write(get_uploaded_file('icon'));
		$filehandler->close();
		
		$thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
		$thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
		$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),100,100, true);
		$thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
		if ($thumbtiny) {
			
			$thumb = new ElggFile();
			$thumb->owner_guid = $group->owner_guid;
			$thumb->setMimeType('image/jpeg');
			
			$thumb->setFilename($prefix."tiny.jpg");
			$thumb->open("write");
			$thumb->write($thumbtiny);
			$thumb->close();
			
			$thumb->setFilename($prefix."small.jpg");
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			
			$thumb->setFilename($prefix."medium.jpg");
			$thumb->open("write");
			$thumb->write($thumbmedium);
			$thumb->close();
			
			$thumb->setFilename($prefix."large.jpg");
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
			
			$group->icontime = time();
		}
	}
	
	system_message(elgg_echo("groups:saved"));
	
	// Forward to the user's profile
	forward($group->getUrl());
	exit;
?>
