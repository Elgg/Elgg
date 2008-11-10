<?php
	/**
	 * Elgg groups plugin language pack
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
			
			'groups' => "Groups",
			'groups:owned' => "Groups you own",
			'groups:yours' => "Your groups",
			'groups:user' => "%s's groups",
			'groups:all' => "All site groups",
			'groups:new' => "Create a new group",
			'groups:edit' => "Edit group",
	
			'groups:icon' => 'Group icon (leave blank to leave unchanged)',
			'groups:name' => 'Group name',
			'groups:username' => 'Group short name (displayed in URLs, alphanumeric characters only)',
			'groups:description' => 'Description',
			'groups:briefdescription' => 'Brief description',
			'groups:interests' => 'Interests',
			'groups:website' => 'Website',
			'groups:members' => 'Group members',
			'groups:membership' => "Membership",
			'groups:access' => "Access permissions",
			'groups:owner' => "Owner",
	        'groups:widget:num_display' => 'Number of groups to display',
	        'groups:widget:membership' => 'Group membership',
	        'groups:widgets:description' => 'Display the groups you are a member of on your profile',
			'groups:noaccess' => 'No access to group',
			'groups:cantedit' => 'You can not edit this group',
			'groups:saved' => 'Group saved',
	
			'groups:joinrequest' => 'Request membership',
			'groups:join' => 'Join group',
			'groups:leave' => 'Leave group',
			'groups:invite' => 'Invite friends',
			'groups:inviteto' => "Invite friends to '%s'",
			'groups:nofriends' => "You have no friends left who have not been invited to this group.",
	
			'groups:group' => "Group",
			
			'item:object:groupforumtopic' => "Forum topics",
	
			/*
			  Group forum strings
			*/
			
			'group:replies' => 'Replies',
			'groups:forum' => 'Group forum',
			'groups:addtopic' => 'Add a topic',
			'groups:forumlatest' => 'Forum latest',
			'groups:latestdiscussion' => 'Latest discussion',
			'groupspost:success' => 'Your comment was succesfully posted',
			'groups:alldiscussion' => 'Latest discussion',
			'groups:edittopic' => 'Edit topic',
			'groups:topicmessage' => 'Topic message',
			'groups:topicstatus' => 'Topic status',
			'groups:reply' => 'Post a comment',
			'groups:topic' => 'Topic',
			'groups:posts' => 'Posts',
			'groups:lastperson' => 'Last person',
			'groups:when' => 'When',
			'grouptopic:notcreated' => 'No topics have been created.',
			'groups:topicopen' => 'Open',
			'groups:topicclosed' => 'Closed',
			'groups:topicresolved' => 'Resolved',
			'grouptopic:created' => 'Your topic was created.',
			'groupstopic:deleted' => 'The topic has been deleted.',
			'groups:topicsticky' => 'Sticky',
			'groups:topicisclosed' => 'This topic is closed.',
			'groups:topiccloseddesc' => 'This topic has now been closed and is not accepting new comments.',
			'grouptopic:error' => 'Your group topic could not be created. Please try again or contact a system administrator.',
	
			'groups:privategroup' => 'This group is private, requesting membership.',
			'groups:notitle' => 'Groups must have a title',
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
	
			'groups:widgets:entities:title' => "Objects in group",
			'groups:widgets:entities:description' => "List the objects saved in this group",
			'groups:widgets:entities:label:displaynum' => 'List the objects of a group.',
			'groups:widgets:entities:label:pleaseedit' => 'Please configure this widget.',
		
			'groups:forumtopic:edited' => 'Forum topic successfully edited.',
	);
					
	add_translation("en",$english);
?>