<?php

return array(
	'add:object:discussion' => 'Add discussion topic',
	'edit:object:discussion' => 'Edit topic',

	'discussion:latest' => 'Latest discussions',
	'collection:object:discussion:group' => 'Group discussions',
	'discussion:none' => 'No discussions',
	'discussion:updated' => "Last comment by %s %s",

	'discussion:topic:created' => 'The discussion topic was created.',
	'discussion:topic:updated' => 'The discussion topic was updated.',
	'entity:delete:object:discussion:success' => 'Discussion topic has been deleted.',

	'discussion:topic:notfound' => 'Discussion topic not found',
	'discussion:error:notsaved' => 'Unable to save this topic',
	'discussion:error:missing' => 'Both title and message are required fields',
	'discussion:error:permissions' => 'You do not have permissions to perform this action',

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

	'item:object:discussion' => "Discussion topic",
	'collection:object:discussion' => 'Discussion topics',

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
