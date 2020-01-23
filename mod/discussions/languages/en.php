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
	'discussion:topic:notify:body' =>
'%s added a new discussion topic "%s":

%s

View and reply to the discussion topic:
%s
',

	'discussion:comment:notify:summary' => 'New comment in topic: %s',
	'discussion:comment:notify:subject' => 'New comment in topic: %s',
	'discussion:comment:notify:body' =>
'%s commented on the discussion topic "%s":

%s

View and comment on the discussion:
%s
',

	'groups:tool:forum' => 'Enable group discussions',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Topic status',
	'discussion:topic:closed:title' => 'This discussion is closed.',
	'discussion:topic:closed:desc' => 'This discussion is closed and is not accepting new comments.',

	'discussion:topic:description' => 'Topic message',

	// upgrades
	'discussions:upgrade:2017112800:title' => "Migrate discussion replies to comments",
	'discussions:upgrade:2017112800:description' => "Discussion replies used to have their own subtype, this has been unified into comments.",
	'discussions:upgrade:2017112801:title' => "Migrate river activity related to discussion replies",
	'discussions:upgrade:2017112801:description' => "Discussion replies used to have their own subtype, this has been unified into comments.",
);
