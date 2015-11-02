<?php

return array(
	'discussion' => 'Discussions',
	'discussion:add' => 'Add discussion topic',
	'discussion:latest' => 'Latest discussions',
	'discussion:group' => 'Group discussions',
	'discussion:none' => 'No discussions',
	'discussion:reply:title' => 'Reply by %s',
	'discussion:new' => "Add discussion post",
	'discussion:updated' => "Last reply by %s %s",

	'discussion:topic:created' => 'The discussion topic was created.',
	'discussion:topic:updated' => 'The discussion topic was updated.',
	'discussion:topic:deleted' => 'Discussion topic has been deleted.',

	'discussion:topic:notfound' => 'Discussion topic not found',
	'discussion:error:notsaved' => 'Unable to save this topic',
	'discussion:error:missing' => 'Both title and message are required fields',
	'discussion:error:permissions' => 'You do not have permissions to perform this action',
	'discussion:error:notdeleted' => 'Could not delete the discussion topic',

	'discussion:reply:edit' => 'Edit reply',
	'discussion:reply:deleted' => 'Discussion reply has been deleted.',
	'discussion:reply:error:notfound' => 'The discussion reply was not found',
	'discussion:reply:error:notfound_fallback' => "Sorry, we could not find the specified reply, but we've forwarded you to the original discussion topic.",
	'discussion:reply:error:notdeleted' => 'Could not delete the discussion reply',

	'discussion:search:title' => 'Reply on topic: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'You cannot post an empty reply',
	'discussion:reply:topic_not_found' => 'The discussion topic was not found',
	'discussion:reply:error:cannot_edit' => 'You do not have the permission to edit this reply',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s added a new discussion topic %s',
	'river:reply:object:discussion' => '%s replied on the discussion topic %s',
	'river:reply:view' => 'view reply',

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

	'discussion:reply:notify:summary' => 'New reply in topic: %s',
	'discussion:reply:notify:subject' => 'New reply in topic: %s',
	'discussion:reply:notify:body' =>
'%s replied to the discussion topic "%s":

%s

View and reply to the discussion:
%s
',

	'item:object:discussion' => "Discussion topics",
	'item:object:discussion_reply' => "Discussion replies",

	'groups:enableforum' => 'Enable group discussions',

	'reply:this' => 'Reply to this',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Group Discussions',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Topic status',
	'discussion:topic:closed:title' => 'This discussion is closed.',
	'discussion:topic:closed:desc' => 'This discussion is closed and is not accepting new comments.',

	'discussion:replies' => 'Replies',
	'discussion:addtopic' => 'Add a topic',
	'discussion:post:success' => 'Your reply was succesfully posted',
	'discussion:post:failure' => 'There was problem while posting your reply',
	'discussion:topic:edit' => 'Edit topic',
	'discussion:topic:description' => 'Topic message',

	'discussion:reply:edited' => "You have successfully edited the forum post.",
	'discussion:reply:error' => "There was a problem editing the forum post.",
);
