<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	
	'relationship:friendrequest' => "%s requested friendship with %s",
	'relationship:friendrequest:pending' => "%s wants to be your friend",
	'relationship:friendrequest:sent' => "You requested friendship with %s",
	
	// plugin settings
	'friends:settings:request:description' => "By default any user can befriend any other user, it's like following the activity of the other user.
After enabling friendship requests when user A wants to be friends with user B, user B has to approve the request. Upon approval user A will be friends with user B and user B will be friends with user A.",
	'friends:settings:request:label' => "Enable friendship requests",
	'friends:settings:request:help' => "Users need to approve a friend request and friendships become bi-directional",
	
	'friends:owned' => "%s's friends",
	'friend:add' => "Add friend",
	'friend:remove' => "Remove friend",
	'friends:menu:request:status:pending' => "Friendship request pending",

	'friends:add:successful' => "You have successfully added %s as a friend.",
	'friends:add:duplicate' => "You're already friends with %s",
	'friends:add:failure' => "We couldn't add %s as a friend.",
	'friends:request:successful' => 'A friendship request was sent to %s',
	'friends:request:error' => 'An error occured while processing your friendship request with %s',

	'friends:remove:successful' => "You have successfully removed %s from your friends.",
	'friends:remove:no_friend' => "You and %s are not friends",
	'friends:remove:failure' => "We couldn't remove %s from your friends.",

	'friends:none' => "No friends yet.",
	'friends:of:owned' => "People who have made %s a friend",

	'friends:of' => "Friends of",
	
	'friends:request:pending' => "Pending friendship requests",
	'friends:request:pending:none' => "No pending friendship requests found.",
	'friends:request:sent' => "Sent friendship requests",
	'friends:request:sent:none' => "No friendship requests have been sent.",
	
	'friends:num_display' => "Number of friends to display",
	
	'widgets:friends:name' => "Friends",
	'widgets:friends:description' => "Displays some of your friends.",
	
	'widgets:friends_of:name' => "Friends of",
	'widgets:friends_of:description' => "Show who made you a friend",
	
	'friends:notification:request:subject' => "%s wants to be your friend!",
	'friends:notification:request:message' => "%s has requested to be your friend on %s.

To view the friendship request, click here:
%s",
	
	'friends:notification:request:decline:subject' => "%s has declined your friendship request",
	'friends:notification:request:decline:message' => "%s has declined your friendship request.",
	
	'friends:notification:request:accept:subject' => "%s has accepted your friendship request",
	'friends:notification:request:accept:message' => "%s has accepted your friendship request.",
	
	'friends:action:friendrequest:revoke:fail' => "An error occured while revoking the friendship request, please try again",
	'friends:action:friendrequest:revoke:success' => "The friendship request has been revoked",
	
	'friends:action:friendrequest:decline:fail' => "An error occured while declining the friendship request, please try again",
	'friends:action:friendrequest:decline:success' => "The friendship request has been declined",
	
	'friends:action:friendrequest:accept:success' => "The friendship request has been accepted",
	
	// notification settings
	'friends:notification:settings:description' => 'Default notification settings for users you add as a friend',
);
