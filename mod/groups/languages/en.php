<?php
return array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Groups",
	'groups:owned' => "Groups I own",
	'groups:owned:user' => 'Groups %s owns',
	'groups:yours' => "My groups",
	'groups:user' => "%s's groups",
	'groups:all' => "All groups",
	'groups:add' => "Create a new group",
	'groups:edit' => "Edit group",
	'groups:delete' => 'Delete group',
	'groups:membershiprequests' => 'Manage join requests',
	'groups:membershiprequests:pending' => 'Manage join requests (%s)',
	'groups:invitations' => 'Group invitations',
	'groups:invitations:pending' => 'Group invitations (%s)',

	'groups:icon' => 'Group icon (leave blank to leave unchanged)',
	'groups:name' => 'Group name',
	'groups:description' => 'Description',
	'groups:briefdescription' => 'Brief description',
	'groups:interests' => 'Tags',
	'groups:website' => 'Website',
	'groups:members' => 'Group members',

	'groups:members_count' => '%s members',

	'groups:members:title' => 'Members of %s',
	'groups:members:more' => "View all members",
	'groups:membership' => "Group membership permissions",
	'groups:content_access_mode' => "Accessibility of group content",
	'groups:content_access_mode:warning' => "Warning: Changing this setting won't change the access permission of existing group content.",
	'groups:content_access_mode:unrestricted' => "Unrestricted - Access depends on content-level settings",
	'groups:content_access_mode:membersonly' => "Members Only - Non-members can never access group content",
	'groups:access' => "Access permissions",
	'groups:owner' => "Owner",
	'groups:owner:warning' => "Warning: if you change this value, you will no longer be the owner of this group.",
	'groups:widget:num_display' => 'Number of groups to display',
	'widgets:a_users_groups:name' => 'Group membership',
	'widgets:a_users_groups:description' => 'Display the groups you are a member of on your profile',

	'groups:noaccess' => 'No access to group',
	'groups:cantcreate' => 'You can not create a group. Only admins can.',
	'groups:cantedit' => 'You can not edit this group',
	'groups:saved' => 'Group saved',
	'groups:save_error' => 'Group could not be saved',
	'groups:featured' => 'Featured groups',
	'groups:makeunfeatured' => 'Unfeature',
	'groups:makefeatured' => 'Make featured',
	'groups:featuredon' => '%s is now a featured group.',
	'groups:unfeatured' => '%s has been removed from the featured groups.',
	'groups:featured_error' => 'Invalid group.',
	'groups:nofeatured' => 'No featured groups',
	'groups:joinrequest' => 'Request membership',
	'groups:join' => 'Join group',
	'groups:leave' => 'Leave group',
	'groups:invite' => 'Invite friends',
	'groups:invite:title' => 'Invite friends to this group',
	'groups:invite:friends:help' => 'Search for a friend by name or username and select the friend from the list',
	'groups:invite:resend' => 'Resend the invitations to already invited users',

	'groups:nofriendsatall' => 'You have no friends to invite!',
	'groups:group' => "Group",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Search for groups with '%s'",
	'groups:search:none' => "No matching groups were found",
	'groups:search_in_group' => "Search in this group",
	'groups:acl' => "Group: %s",
	'groups:acl:in_context' => 'Group members',

	'groups:notfound' => "Group not found",
	
	'groups:requests:none' => 'There are no current membership requests.',

	'groups:invitations:none' => 'There are no current invitations.',

	'groups:open' => "open group",
	'groups:closed' => "closed group",
	'groups:member' => "members",
	'groups:search' => "Search for groups",

	'groups:more' => 'More groups',
	'groups:none' => 'No groups',

	/**
	 * Access
	 */
	'groups:access:private' => 'Closed - Users must be invited',
	'groups:access:public' => 'Open - Any user may join',
	'groups:access:group' => 'Group members only',
	'groups:closedgroup' => "This group's membership is closed.",
	'groups:closedgroup:request' => 'To ask to be added, click the "Request membership" menu link.',
	'groups:closedgroup:membersonly' => "This group's membership is closed and its content is accessible only by members.",
	'groups:opengroup:membersonly' => "This group's content is accessible only by members.",
	'groups:opengroup:membersonly:join' => 'To be a member, click the "Join group" menu link.',
	'groups:visibility' => 'Who can see this group?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Groups',

	'groups:notitle' => 'Groups must have a title',
	'groups:cantjoin' => 'Can not join group',
	'groups:cantleave' => 'Could not leave group',
	'groups:removeuser' => 'Remove from group',
	'groups:cantremove' => 'Cannot remove user from group',
	'groups:removed' => 'Successfully removed %s from group',
	'groups:addedtogroup' => 'Successfully added the user to the group',
	'groups:joinrequestnotmade' => 'Could not request to join group',
	'groups:joinrequestmade' => 'Requested to join group',
	'groups:joinrequest:exists' => 'You already requested membership for this group',
	'groups:button:joined' => 'Joined',
	'groups:button:owned' => 'Owned',
	'groups:joined' => 'Successfully joined group!',
	'groups:left' => 'Successfully left group',
	'groups:userinvited' => 'User has been invited.',
	'groups:usernotinvited' => 'User could not be invited.',
	'groups:useralreadyinvited' => 'User has already been invited',
	'groups:invite:subject' => "%s you have been invited to join %s!",
	'groups:joinrequest:remove:check' => 'Are you sure you want to remove this join request?',
	'groups:invite:remove:check' => 'Are you sure you want to remove this invitation?',
	'groups:invite:body' => "Hi %s,

%s invited you to join the '%s' group.

Click below to view your invitations:
%s",

	'groups:welcome:subject' => "Welcome to the %s group!",
	'groups:welcome:body' => "Hi %s!

You are now a member of the '%s' group.

Click below to begin posting!
%s",

	'groups:request:subject' => "%s has requested to join %s",
	'groups:request:body' => "Hi %s,

%s has requested to join the '%s' group.

Click below to view their profile:
%s

or click below to view the group's join requests:
%s",

	'river:group:create' => '%s created the group %s',
	'river:group:join' => '%s joined the group %s',

	'groups:allowhiddengroups' => 'Allow private (invisible) groups?',
	'groups:whocancreate' => 'Who can create new groups?',

	/**
	 * Action messages
	 */
	'groups:deleted' => 'Group and group contents deleted',
	'groups:notdeleted' => 'Group could not be deleted',
	'groups:deletewarning' => "Are you sure you want to delete this group? There is no undo!",

	'groups:invitekilled' => 'The invite has been deleted.',
	'groups:joinrequestkilled' => 'The join request has been deleted.',
	'groups:error:addedtogroup' => "Could not add %s to the group",
	'groups:add:alreadymember' => "%s is already a member of this group",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Group profiles',

	/**
	 * Upgrades
	 */
	'groups:upgrade:2016101900:title' => 'Transfer group icons to new location',
	'groups:upgrade:2016101900:description' => 'New entity icon API stores icons in a predictable location on the filestore
relative to the entity\'s filestore directory. This upgrade aligns will align group plugin with the requirements of the new API.',
);
