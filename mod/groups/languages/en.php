<?php
/**
 * Elgg groups plugin language pack
 *
 * @package ElggGroups
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Groups",
	'groups:owned' => "Groups I own",
	'groups:yours' => "My groups",
	'groups:user' => "%s's groups",
	'groups:all' => "All groups",
	'groups:add' => "Create a new group",
	'groups:edit' => "Edit group",
	'groups:delete' => 'Delete group',
	'groups:membershiprequests' => 'Manage join requests',
	'groups:invitations' => 'Group invitations',

	'groups:icon' => 'Group icon (leave blank to leave unchanged)',
	'groups:name' => 'Group name',
	'groups:username' => 'Group short name (displayed in URLs, alphanumeric characters only)',
	'groups:description' => 'Description',
	'groups:briefdescription' => 'Brief description',
	'groups:interests' => 'Tags',
	'groups:website' => 'Website',
	'groups:members' => 'Group members',
	'groups:members:title' => 'Members of %s',
	'groups:members:more' => "View all members",
	'groups:membership' => "Group membership permissions",
	'groups:access' => "Access permissions",
	'groups:owner' => "Owner",
	'groups:widget:num_display' => 'Number of groups to display',
	'groups:widget:membership' => 'Group membership',
	'groups:widgets:description' => 'Display the groups you are a member of on your profile',
	'groups:noaccess' => 'No access to group',
	'groups:permissions:error' => 'You do not have the permissions for this',
	'groups:ingroup' => 'in the group',
	'groups:cantedit' => 'You can not edit this group',
	'groups:saved' => 'Group saved',
	'groups:featured' => 'Featured groups',
	'groups:makeunfeatured' => 'Unfeature',
	'groups:makefeatured' => 'Make featured',
	'groups:featuredon' => '%s is now a featured group.',
	'groups:unfeatured' => '%s has been removed from the featured groups.',
	'groups:featured_error' => 'Invalid group.',
	'groups:joinrequest' => 'Request membership',
	'groups:join' => 'Join group',
	'groups:leave' => 'Leave group',
	'groups:invite' => 'Invite friends',
	'groups:invite:title' => 'Invite friends to this group',
	'groups:inviteto' => "Invite friends to '%s'",
	'groups:nofriends' => "You have no friends left who have not been invited to this group.",
	'groups:nofriendsatall' => 'You have no friends to invite!',
	'groups:viagroups' => "via groups",
	'groups:group' => "Group",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Search for groups tagged with '%s'",
	'groups:search:none' => "No matching groups were found",

	'groups:activity' => "Group activity",
	'groups:enableactivity' => 'Enable group activity',
	'groups:activity:none' => "There is no group activity yet",

	'groups:notfound' => "Group not found",
	'groups:notfound:details' => "The requested group either does not exist or you do not have access to it",

	'groups:requests:none' => 'There are no outstanding membership requests at this time.',

	'groups:invitations:none' => 'There are no outstanding invitations at this time.',

	'item:object:groupforumtopic' => "Discussion topics",

	'groupforumtopic:new' => "Add discussion post",

	'groups:count' => "groups created",
	'groups:open' => "open group",
	'groups:closed' => "closed group",
	'groups:member' => "members",
	'groups:searchtag' => "Search for groups by tag",

	'groups:more' => 'More groups',
	'groups:none' => 'No groups',


	/*
	 * Access
	 */
	'groups:access:private' => 'Closed - Users must be invited',
	'groups:access:public' => 'Open - Any user may join',
	'groups:access:group' => 'Group members only',
	'groups:closedgroup' => 'This group has a closed membership.',
	'groups:closedgroup:request' => 'To ask to be added, click the "request membership" menu link.',
	'groups:visibility' => 'Who can see this group?',

	/*
	Group tools
	*/
	'groups:enableforum' => 'Enable group discussion',
	'groups:yes' => 'yes',
	'groups:no' => 'no',
	'groups:lastupdated' => 'Last updated %s by %s',
	'groups:lastcomment' => 'Last comment %s by %s',

	/*
	Group discussion
	*/
	'discussion' => 'Discussion',
	'discussion:add' => 'Add discussion topic',
	'discussion:latest' => 'Latest discussion',
	'discussion:group' => 'Group discussion',

	'discussion:topic:created' => 'The discussion topic was created.',
	'discussion:topic:updated' => 'The discussion topic was updated.',
	'discussion:topic:deleted' => 'Discussion topic has been deleted.',

	'discussion:topic:notfound' => 'Discussion topic not found',
	'discussion:error:notsaved' => 'Unable to save this topic',
	'discussion:error:missing' => 'Both title and message are required fields',
	'discussion:error:permissions' => 'You do not have permissions to perform this action',
	'discussion:error:notdeleted' => 'Could not delete the discussion topic',

	'discussion:reply:deleted' => 'Discussion reply has been deleted.',
	'discussion:reply:error:notdeleted' => 'Could not delete the discussion reply',

	'reply:this' => 'Reply to this',

	'group:replies' => 'Replies',
	'groups:forum:created' => 'Created %s with %d comments',
	'groups:forum:created:single' => 'Created %s with %d reply',
	'groups:forum' => 'Discussion',
	'groups:addtopic' => 'Add a topic',
	'groups:forumlatest' => 'Latest discussion',
	'groups:latestdiscussion' => 'Latest discussion',
	'groups:newest' => 'Newest',
	'groups:popular' => 'Popular',
	'groupspost:success' => 'Your reply was succesfully posted',
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
	'groups:topicisclosed' => 'This discussion is closed.',
	'groups:topiccloseddesc' => 'This discussion is closed and is not accepting new comments.',
	'grouptopic:error' => 'Your group topic could not be created. Please try again or contact a system administrator.',
	'groups:forumpost:edited' => "You have successfully edited the forum post.",
	'groups:forumpost:error' => "There was a problem editing the forum post.",


	'groups:privategroup' => 'This group is closed. Requesting membership.',
	'groups:notitle' => 'Groups must have a title',
	'groups:cantjoin' => 'Can not join group',
	'groups:cantleave' => 'Could not leave group',
	'groups:removeuser' => 'Remove from group',
	'groups:cantremove' => 'Cannot remove user from group',
	'groups:removed' => 'Successfully removed %s from group',
	'groups:addedtogroup' => 'Successfully added the user to the group',
	'groups:joinrequestnotmade' => 'Could not request to join group',
	'groups:joinrequestmade' => 'Requested to join group',
	'groups:joined' => 'Successfully joined group!',
	'groups:left' => 'Successfully left group',
	'groups:notowner' => 'Sorry, you are not the owner of this group.',
	'groups:notmember' => 'Sorry, you are not a member of this group.',
	'groups:alreadymember' => 'You are already a member of this group!',
	'groups:userinvited' => 'User has been invited.',
	'groups:usernotinvited' => 'User could not be invited.',
	'groups:useralreadyinvited' => 'User has already been invited',
	'groups:invite:subject' => "%s you have been invited to join %s!",
	'groups:updated' => "Last reply by %s %s",
	'groups:started' => "Started by %s",
	'groups:joinrequest:remove:check' => 'Are you sure you want to remove this join request?',
	'groups:invite:remove:check' => 'Are you sure you want to remove this invite?',
	'groups:invite:body' => "Hi %s,

%s invited you to join the '%s' group. Click below to view your invitations:

%s",

	'groups:welcome:subject' => "Welcome to the %s group!",
	'groups:welcome:body' => "Hi %s!

You are now a member of the '%s' group! Click below to begin posting!

%s",

	'groups:request:subject' => "%s has requested to join %s",
	'groups:request:body' => "Hi %s,

%s has requested to join the '%s' group. Click below to view their profile:

%s

or click below to view the group's join requests:

%s",

	/*
		Forum river items
	*/

	'river:create:group:default' => '%s created the group %s',
	'river:join:group:default' => '%s joined the group %s',
	'river:create:object:groupforumtopic' => '%s added a new discussion topic %s',
	'river:reply:object:groupforumtopic' => '%s replied on the discussion topic %s',
	
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

	'groups:allowhiddengroups' => 'Do you want to allow private (invisible) groups?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Group and group contents deleted',
	'group:notdeleted' => 'Group could not be deleted',

	'group:notfound' => 'Could not find the group',
	'grouppost:deleted' => 'Group posting successfully deleted',
	'grouppost:notdeleted' => 'Group posting could not be deleted',
	'groupstopic:deleted' => 'Topic deleted',
	'groupstopic:notdeleted' => 'Topic not deleted',
	'grouptopic:blank' => 'No topic',
	'grouptopic:notfound' => 'Could not find the topic',
	'grouppost:nopost' => 'Empty post',
	'groups:deletewarning' => "Are you sure you want to delete this group? There is no undo!",

	'groups:invitekilled' => 'The invite has been deleted.',
	'groups:joinrequestkilled' => 'The join request has been deleted.',

	// ecml
	'groups:ecml:discussion' => 'Group Discussions',
	'groups:ecml:groupprofile' => 'Group profiles',

);

add_translation("en", $english);