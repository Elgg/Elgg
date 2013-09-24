<?php
/**
 * Language file for discussion plugin
 */

return array(
	'discussion' => 'Discussion',
	'discussion:all' => 'Discussions',
	'discussion:add' => 'Add discussion topic',
	'discussion:latest' => 'Latest discussion',
	'discussion:none' => 'No discussions',

	'discussion:title:all' => 'All discussions',
	'discussion:title:friends' => 'Discussions started by your friends',
	'discussion:title:owned' => 'Discussions started by you',
	'discussion:title:owner:user' => 'Discussions started by %s',
	'discussion:title:owner:group' => 'Discussions of group %s',

	'discussion:topic:created' => 'The discussion topic was created.',
	'discussion:topic:updated' => 'The discussion topic was updated.',
	'discussion:topic:deleted' => 'Discussion topic has been deleted.',

	'discussion:topic:notfound' => 'Discussion topic not found',
	'discussion:error:notsaved' => 'Unable to save this topic',
	'discussion:error:missing' => 'Both title and message are required fields',
	'discussion:error:permissions' => 'You do not have permissions to perform this action',
	'discussion:error:notdeleted' => 'Could not delete the discussion topic',

	// TODO These are not needed once replies become ElggComment objects in 1.9
	'discussion:reply:deleted' => 'Discussion reply has been deleted.',
	'discussion:reply:error:notdeleted' => 'Could not delete the discussion reply',

	'discussion:started' => "Started by %s",
	'discussion:updated' => "Last reply by %s %s",
	'discussion:replies' => 'Replies',
	'discussion:edit' => 'Edit topic',
	'discussion:description' => 'Topic message',
	'discussion:closed' => 'This discussion is closed.',
	'discussion:closed:desc' => 'This discussion is closed and is not accepting new comments.',

	'discussion:group' => 'Group discussion',
	'discussion:enablediscussion' => 'Enable group discussion',
	'discussion:groups:latest' => 'Latest discussion',

	'item:object:discussion' => 'Discussions',

	/**
	 * River items
	 */
	'river:create:object:discussion' => '%s added a new discussion topic %s',
	'river:comment:object:discussion' => '%s replied to the discussion %s',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'New discussion topic called %s',
	'discussion:topic:notify:subject' => 'New discussion topic: %s',
	'discussion:topic:notify:body' =>
'%s added a new discussion topic:

Title: %s

%s

View and reply to the discussion topic:
%s
',

	'discussion:reply:notify:summary' => 'New discussion reply in topic: %s',
	'discussion:reply:notify:subject' => 'New discussion reply in topic: %s',
	'discussion:reply:notify:body' =>
'%s replied to the discussion topic %s:

%s

View and reply to the discussion:
%s
',
);