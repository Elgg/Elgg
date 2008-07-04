<?php
	/**
	 * Elgg groups plugin language pack
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'groups' => "Groups",
			'groups:yours' => "Your groups",
			'groups:user' => "%s's groups",
			'groups:all' => "All groups",
			'groups:new' => "Create a new group",
			'groups:edit' => "Edit a group",
	
			'groups:title' => 'Group title',
			'groups:description' => 'Description',
			'groups:interests' => 'Interests',
			'groups:website' => 'Website',
			'groups:membership' => "Membership",
			'groups:owner' => "Owner",
	
			'groups:noaccess' => 'No access to group',
			'groups:cantedit' => 'You can not edit this group',
			'groups:saved' => 'Group saved',
	
			'groups:joinrequest' => 'Request membership',
			'groups:join' => 'Join group',
			'groups:leave' => 'Leave group',
	
	
			'groups:privategroup' => 'This group is private, requesting membership.',
			'groups:cantjoin' => 'Can not join group',
			'groups:cantleave' => 'Could not leave group',
			'groups:addedtogroup' => 'Successfully added the user to the group',
			'groups:joinrequestnotmade' => 'Join request could not be made',
			'groups:joinrequestmade' => 'Request to join group successfully made',
			'groups:joined' => 'Successfully joined group!',
			'groups:left' => 'Successfully left group',
			'groups:notowner' => 'Sorry, you are not the owner of this group.',
			'groups:alreadymember' => 'You are already a member of this group!',
			'groups:userinvited' => 'User has been invited.',
			'groups:usernotinvited' => 'User could not be invited.',
	
			'groups:invite:subject' => "%s you have been invited to join %s!",
			'groups:invite:body' => "Hi %s,

You have been invited to join the '%s' group, click below to confirm:

%s",

			'groups:welcome:subject' => "Welcome to the %s group!",
			'groups:welcome:body' => "Hi %s!
		
You are now a member of the '%s' group! Click below to begin posting!

%s",
	
			'groups:request:subject' => "%s has requested to join %s",
			'groups:request:body' => "Hi %s,

%s has requested to join the '%s' group, click below to view their profile:

%s

or click below to confirm request:

%s",
	
			'groups:river:member' => 'is now a member of',
	
			'groups:nowidgets' => 'No widgets have been defined for this group.',
	
	
			'groups:widgets:members:title' => 'Group members',
			'groups:widgets:members:description' => 'List the members of a group.',
			'groups:widgets:members:label:displaynum' => 'List the members of a group.',
			'groups:widgets:members:label:pleaseedit' => 'Please configure this widget.',
		
	);
					
	add_translation("en",$english);
?>