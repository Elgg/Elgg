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
	'groups:username' => 'Group short name (displayed in URLs, alphanumeric characters only)',
	'groups:description' => 'Description',
	'groups:briefdescription' => 'Brief description',
	'groups:interests' => 'Tags',
	'groups:website' => 'Website',
	'groups:members' => 'Group members',
	'groups:my_status' => 'My status',
	'groups:my_status:group_owner' => 'You own this group',
	'groups:my_status:group_member' => 'You are in this group',
	'groups:subscribed' => 'Group notifications on',
	'groups:unsubscribed' => 'Group notifications off',

	'groups:members:title' => 'Members of %s',
	'groups:members:more' => "View all members",
	'groups:membership' => "Group membership permissions",
	'groups:access' => "Access permissions",
	'groups:owner' => "Owner",
	'groups:owner:warning' => "Warning: if you change this value, you will no longer be the owner of this group.",
	'groups:widget:num_display' => 'Number of groups to display',
	'groups:widget:membership' => 'Group membership',
	'groups:widgets:description' => 'Display the groups you are a member of on your profile',
	'groups:noaccess' => 'No access to group',
	'groups:permissions:error' => 'You do not have the permissions for this',
	'groups:ingroup' => 'in the group',
	'groups:cantcreate' => 'You can not create a group. Only admins can.',
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
	'groups:search_in_group' => "Search in this group",
	'groups:acl' => "Group: %s",

	'groups:activity' => "Group activity",
	'groups:enableactivity' => 'Enable group activity',
	'groups:activity:none' => "There is no group activity yet",

	'groups:notfound' => "Group not found",
	'groups:notfound:details' => "The requested group either does not exist or you do not have access to it",

	'groups:requests:none' => 'There are no current membership requests.',

	'groups:invitations:none' => 'There are no current invitations.',

	'groups:count' => "groups created",
	'groups:open' => "open group",
	'groups:closed' => "closed group",
	'groups:member' => "members",
	'groups:searchtag' => "Search for groups by tag",

	'groups:more' => 'More groups',
	'groups:none' => 'No groups',

);

add_translation("en", $english);
