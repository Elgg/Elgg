<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Discussion topic",
	
	'add:object:discussion' => 'Add discussion topic',
	'edit:object:discussion' => 'Edit topic',
	'collection:object:discussion' => 'Discussion topics',
	'collection:object:discussion:group' => 'Group discussions',
	'collection:object:discussion:my_groups' => 'Discussions in my groups',
	'notification:object:discussion:create' => "Send a notification when a discussion is created",
	'notifications:mute:object:discussion' => "about the discussion '%s'",
	
	'discussion:settings:enable_global_discussions' => 'Enable global discussions',
	'discussion:settings:enable_global_discussions:help' => 'Allow discussions to be created outside of groups',

	'discussion:latest' => 'Latest discussions',
	'discussion:none' => 'No discussions',
	'discussion:updated' => "Last comment by %s %s",

	'discussion:topic:created' => 'The discussion topic was created.',
	'discussion:topic:updated' => 'The discussion topic was updated.',
	'entity:delete:object:discussion:success' => 'Discussion topic has been deleted.',

	'discussion:topic:notfound' => 'Discussion topic not found',
	'discussion:error:notsaved' => 'Unable to save this topic',
	'discussion:error:missing' => 'Both title and message are required fields',
	'discussion:error:permissions' => 'You do not have permissions to perform this action',
	'discussion:error:no_groups' => "You're not a member of any groups.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s added a new discussion topic %s',
	'river:object:discussion:comment' => '%s commented on the discussion topic %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'New discussion topic called %s',
	'discussion:topic:notify:subject' => 'New discussion topic: %s',
	'discussion:topic:notify:body' => '%s added a new discussion topic "%s":

%s

View and reply to the discussion topic:
%s',

	'discussion:comment:notify:summary' => 'New comment in topic: %s',
	'discussion:comment:notify:subject' => 'New comment in topic: %s',
	'discussion:comment:notify:body' => '%s commented on the discussion topic "%s":

%s

View and comment on the discussion:
%s',
	
	'notification:mentions:object:discussion:subject' => '%s mentioned you in a discussion',

	'groups:tool:forum' => 'Enable group discussions',
	'groups:tool:forum:description' => 'Allow group members to start a discussion in this group.',

	'discussions:groups:edit:add_group_subscribers_to_discussion_comments' => 'Add group subscriber to discussion comment notifications',
	
	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Topic status',
	'discussion:topic:closed:title' => 'This discussion is closed.',
	'discussion:topic:closed:desc' => 'This discussion is closed and is not accepting new comments.',
	'discussion:topic:container' => 'Select an optional group to start this discussion',
	'discussion:topic:container:help' => 'Starting a discussion in a group will limit the access to group members by default',

	'discussion:topic:description' => 'Topic message',
	'discussion:topic:toggle_status:open' => 'The discussion topic was successfully reopened',
	'discussion:topic:toggle_status:open:confirm' => 'Are you sure you wish to reopen this topic?',
	'discussion:topic:toggle_status:closed' => 'The discussion topic was successfully closed',
	'discussion:topic:toggle_status:closed:confirm' => 'Are you sure you wish to close this topic?',
	
	// widgets
	'widgets:discussions:name' => 'Discussions',
	'widgets:discussions:description' => 'Shows recent discussions',
);
