<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	'thewire' => "The Wire",

	'item:object:thewire' => "Wire post",
	'collection:object:thewire' => 'Wire posts',
	'collection:object:thewire:all' => "All wire posts",
	'collection:object:thewire:owner' => "%s's wire posts",
	'collection:object:thewire:friends' => "Friends' wire posts",
	'collection:object:thewire:mentions' => "Wire posts mentioning @%s",
	'notification:object:thewire:create' => "Send a notification when a wire post is created",
	'notifications:mute:object:thewire' => "about the wire post '%s'",
	
	'entity:edit:object:thewire:success' => 'The wire post was saved successfully',

	'thewire:menu:filter:mentions' => "Mentions",
	
	'thewire:replying' => "Replying to %s (@%s) who wrote",
	'thewire:thread' => "Thread",
	'thewire:charleft' => "characters remaining",
	'thewire:tags' => "Wire posts tagged with '%s'",
	'thewire:noposts' => "No wire posts yet",

	'thewire:by' => 'Wire post by %s',

	'thewire:form:body:placeholder' => "What's happening?",
	
	/**
	 * The wire river
	 */
	'river:object:thewire:create' => "%s posted to %s",
	'thewire:wire' => 'the wire',

	/**
	 * Wire widget
	 */
	
	'widgets:thewire:description' => 'Display your latest wire posts',
	'thewire:num' => 'Number of posts to display',
	'thewire:moreposts' => 'More wire posts',

	/**
	 * Status messages
	 */
	'thewire:posted' => "Your message was successfully posted to the wire.",
	'thewire:blank' => "Sorry, you need to enter some text before we can post this.",
	'thewire:notsaved' => "Sorry. We could not save this wire post.",

	/**
	 * Notifications
	 */
	'thewire:notify:summary' => 'New wire post: %s',
	'thewire:notify:subject' => "New wire post from %s",
	'thewire:notify:reply' => '%s responded to %s on the wire:',
	'thewire:notify:post' => '%s posted on the wire:',
	'thewire:notify:footer' => "View and reply:\n%s",
	
	'notification:mentions:object:thewire:subject' => '%s mentioned you in a wire post',

	/**
	 * Settings
	 */
	'thewire:settings:limit' => "Maximum number of characters for wire messages:",
	'thewire:settings:limit:none' => "No limit",
	
	/**
	 * Exceptions
	 */
	'ValidationException:thewire:limit' => "The wire post length is over the configured limit",
);
