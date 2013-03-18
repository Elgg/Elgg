<?php
/**
 * Elgg projects plugin language pack
 *
 * @package ElggGroups
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'projects' => "Groups",
	'projects:owned' => "Groups I own",
	'projects:owned:user' => 'Groups %s owns',
	'projects:yours' => "My projects",
	'projects:user' => "%s's projects",
	'projects:all' => "All projects",
	'projects:add' => "Create a new project",
	'projects:edit' => "Edit project",
	'projects:delete' => 'Delete project',
	'projects:membershiprequests' => 'Manage join requests',
	'projects:membershiprequests:pending' => 'Manage join requests (%s)',
	'projects:invitations' => 'Group invitations',
	'projects:invitations:pending' => 'Group invitations (%s)',

	'projects:icon' => 'Group icon (leave blank to leave unchanged)',
	'projects:name' => 'Group name',
	'projects:username' => 'Group short name (displayed in URLs, alphanumeric characters only)',
	'projects:description' => 'Description',
	'projects:briefdescription' => 'Brief description',
	'projects:interests' => 'Tags',
	'projects:website' => 'Website',
	'projects:members' => 'Group members',
	'projects:my_status' => 'My status',
	'projects:my_status:project_owner' => 'You own this project',
	'projects:my_status:project_member' => 'You are in this project',
	'projects:subscribed' => 'Group notifications on',
	'projects:unsubscribed' => 'Group notifications off',

	'projects:members:title' => 'Members of %s',
	'projects:members:more' => "View all members",
	'projects:membership' => "Group membership permissions",
	'projects:access' => "Access permissions",
	'projects:owner' => "Owner",
	'projects:owner:warning' => "Warning: if you change this value, you will no longer be the owner of this project.",
	'projects:widget:num_display' => 'Number of projects to display',
	'projects:widget:membership' => 'Group membership',
	'projects:widgets:description' => 'Display the projects you are a member of on your profile',
	'projects:noaccess' => 'No access to project',
	'projects:permissions:error' => 'You do not have the permissions for this',
	'projects:inproject' => 'in the project',
	'projects:cantcreate' => 'You can not create a project. Only admins can.',
	'projects:cantedit' => 'You can not edit this project',
	'projects:saved' => 'Group saved',
	'projects:featured' => 'Featured projects',
	'projects:makeunfeatured' => 'Unfeature',
	'projects:makefeatured' => 'Make featured',
	'projects:featuredon' => '%s is now a featured project.',
	'projects:unfeatured' => '%s has been removed from the featured projects.',
	'projects:featured_error' => 'Invalid project.',
	'projects:joinrequest' => 'Request membership',
	'projects:join' => 'Join project',
	'projects:leave' => 'Leave project',
	'projects:invite' => 'Invite friends',
	'projects:invite:title' => 'Invite friends to this project',
	'projects:inviteto' => "Invite friends to '%s'",
	'projects:nofriends' => "You have no friends left who have not been invited to this project.",
	'projects:nofriendsatall' => 'You have no friends to invite!',
	'projects:viaprojects' => "via projects",
	'projects:project' => "Group",
	'projects:search:tags' => "tag",
	'projects:search:title' => "Search for projects tagged with '%s'",
	'projects:search:none' => "No matching projects were found",
	'projects:search_in_project' => "Search in this project",
	'projects:acl' => "Group: %s",

	'discussion:notification:topic:subject' => 'New project discussion post',
	'projects:notification' =>
'%s added a new discussion topic to %s:

%s
%s

View and reply to the discussion:
%s
',

	'discussion:notification:reply:body' =>
'%s replied to the discussion topic %s in the project %s:

%s

View and reply to the discussion:
%s
',

	'projects:activity' => "Group activity",
	'projects:enableactivity' => 'Enable project activity',
	'projects:activity:none' => "There is no project activity yet",

	'projects:notfound' => "Group not found",
	'projects:notfound:details' => "The requested project either does not exist or you do not have access to it",

	'projects:requests:none' => 'There are no current membership requests.',

	'projects:invitations:none' => 'There are no current invitations.',

	'item:object:projectforumtopic' => "Discussion topics",

	'projectforumtopic:new' => "Add discussion post",

	'projects:count' => "projects created",
	'projects:open' => "open project",
	'projects:closed' => "closed project",
	'projects:member' => "members",
	'projects:searchtag' => "Search for projects by tag",

	'projects:more' => 'More projects',
	'projects:none' => 'No projects',


	/*
	 * Access
	 */
	'projects:access:private' => 'Closed - Users must be invited',
	'projects:access:public' => 'Open - Any user may join',
	'projects:access:project' => 'Group members only',
	'projects:closedproject' => 'This project has a closed membership.',
	'projects:closedproject:request' => 'To ask to be added, click the "request membership" menu link.',
	'projects:visibility' => 'Who can see this project?',

	/*
	Group tools
	*/
	'projects:enableforum' => 'Enable project discussion',
	'projects:yes' => 'yes',
	'projects:no' => 'no',
	'projects:lastupdated' => 'Last updated %s by %s',
	'projects:lastcomment' => 'Last comment %s by %s',

	/*
	Group discussion
	*/
	'discussion' => 'Discussion',
	'discussion:add' => 'Add discussion topic',
	'discussion:latest' => 'Latest discussion',
	'discussion:project' => 'Group discussion',
	'discussion:none' => 'No discussion',
	'discussion:reply:title' => 'Reply by %s',

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

	'project:replies' => 'Replies',
	'projects:forum:created' => 'Created %s with %d comments',
	'projects:forum:created:single' => 'Created %s with %d reply',
	'projects:forum' => 'Discussion',
	'projects:addtopic' => 'Add a topic',
	'projects:forumlatest' => 'Latest discussion',
	'projects:latestdiscussion' => 'Latest discussion',
	'projects:newest' => 'Newest',
	'projects:popular' => 'Popular',
	'projectspost:success' => 'Your reply was succesfully posted',
	'projects:alldiscussion' => 'Latest discussion',
	'projects:edittopic' => 'Edit topic',
	'projects:topicmessage' => 'Topic message',
	'projects:topicstatus' => 'Topic status',
	'projects:reply' => 'Post a comment',
	'projects:topic' => 'Topic',
	'projects:posts' => 'Posts',
	'projects:lastperson' => 'Last person',
	'projects:when' => 'When',
	'projecttopic:notcreated' => 'No topics have been created.',
	'projects:topicopen' => 'Open',
	'projects:topicclosed' => 'Closed',
	'projects:topicresolved' => 'Resolved',
	'projecttopic:created' => 'Your topic was created.',
	'projectstopic:deleted' => 'The topic has been deleted.',
	'projects:topicsticky' => 'Sticky',
	'projects:topicisclosed' => 'This discussion is closed.',
	'projects:topiccloseddesc' => 'This discussion is closed and is not accepting new comments.',
	'projecttopic:error' => 'Your project topic could not be created. Please try again or contact a system administrator.',
	'projects:forumpost:edited' => "You have successfully edited the forum post.",
	'projects:forumpost:error' => "There was a problem editing the forum post.",


	'projects:privateproject' => 'This project is closed. Requesting membership.',
	'projects:notitle' => 'Groups must have a title',
	'projects:cantjoin' => 'Can not join project',
	'projects:cantleave' => 'Could not leave project',
	'projects:removeuser' => 'Remove from project',
	'projects:cantremove' => 'Cannot remove user from project',
	'projects:removed' => 'Successfully removed %s from project',
	'projects:addedtoproject' => 'Successfully added the user to the project',
	'projects:joinrequestnotmade' => 'Could not request to join project',
	'projects:joinrequestmade' => 'Requested to join project',
	'projects:joined' => 'Successfully joined project!',
	'projects:left' => 'Successfully left project',
	'projects:notowner' => 'Sorry, you are not the owner of this project.',
	'projects:notmember' => 'Sorry, you are not a member of this project.',
	'projects:alreadymember' => 'You are already a member of this project!',
	'projects:userinvited' => 'User has been invited.',
	'projects:usernotinvited' => 'User could not be invited.',
	'projects:useralreadyinvited' => 'User has already been invited',
	'projects:invite:subject' => "%s you have been invited to join %s!",
	'projects:updated' => "Last reply by %s %s",
	'projects:started' => "Started by %s",
	'projects:joinrequest:remove:check' => 'Are you sure you want to remove this join request?',
	'projects:invite:remove:check' => 'Are you sure you want to remove this invitation?',
	'projects:invite:body' => "Hi %s,

%s invited you to join the '%s' project. Click below to view your invitations:

%s",

	'projects:welcome:subject' => "Welcome to the %s project!",
	'projects:welcome:body' => "Hi %s!

You are now a member of the '%s' project! Click below to begin posting!

%s",

	'projects:request:subject' => "%s has requested to join %s",
	'projects:request:body' => "Hi %s,

%s has requested to join the '%s' project. Click below to view their profile:

%s

or click below to view the project's join requests:

%s",

	/*
		Forum river items
	*/

	'river:create:project:default' => '%s created the project %s',
	'river:join:project:default' => '%s joined the project %s',
	'river:create:object:projectforumtopic' => '%s added a new discussion topic %s',
	'river:reply:object:projectforumtopic' => '%s replied on the discussion topic %s',
	
	'projects:nowidgets' => 'No widgets have been defined for this project.',


	'projects:widgets:members:title' => 'Group members',
	'projects:widgets:members:description' => 'List the members of a project.',
	'projects:widgets:members:label:displaynum' => 'List the members of a project.',
	'projects:widgets:members:label:pleaseedit' => 'Please configure this widget.',

	'projects:widgets:entities:title' => "Objects in project",
	'projects:widgets:entities:description' => "List the objects saved in this project",
	'projects:widgets:entities:label:displaynum' => 'List the objects of a project.',
	'projects:widgets:entities:label:pleaseedit' => 'Please configure this widget.',

	'projects:forumtopic:edited' => 'Forum topic successfully edited.',

	'projects:allowhiddenprojects' => 'Do you want to allow private (invisible) projects?',
	'projects:whocancreate' => 'Who can create new projects?',

	/**
	 * Action messages
	 */
	'project:deleted' => 'Group and project contents deleted',
	'project:notdeleted' => 'Group could not be deleted',

	'project:notfound' => 'Could not find the project',
	'projectpost:deleted' => 'Group posting successfully deleted',
	'projectpost:notdeleted' => 'Group posting could not be deleted',
	'projectstopic:deleted' => 'Topic deleted',
	'projectstopic:notdeleted' => 'Topic not deleted',
	'projecttopic:blank' => 'No topic',
	'projecttopic:notfound' => 'Could not find the topic',
	'projectpost:nopost' => 'Empty post',
	'projects:deletewarning' => "Are you sure you want to delete this project? There is no undo!",

	'projects:invitekilled' => 'The invite has been deleted.',
	'projects:joinrequestkilled' => 'The join request has been deleted.',

	// ecml
	'projects:ecml:discussion' => 'Group Discussions',
	'projects:ecml:projectprofile' => 'Group profiles',

);

add_translation("en", $english);
