<?php
/**
 * The Wire English language file
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'thewire' => "The Wire",
	'thewire:everyone' => "All wire posts",
	'thewire:user' => "%s's wire posts",
	'thewire:friends' => "Friends' wire posts",
	'thewire:reply' => "Reply",
	'thewire:replying' => "Replying to %s (@%s) who wrote",
	'thewire:thread' => "Thread",
	'thewire:charleft' => "characters remaining",
	'thewire:tags' => "Wire posts tagged with '%s'",
	'thewire:noposts' => "No wire posts yet",
	'item:object:thewire' => "Wire posts",
	'thewire:update' => 'Update',

	'thewire:previous' => "Previous",
	'thewire:hide' => "Hide",
	'thewire:previous:help' => "View previous post",
	'thewire:hide:help' => "Hide previous post",

	/**
	 * The wire river
	 */
	'river:create:object:thewire' => "%s posted to the wire",

	/**
	 * Wire widget
	 */
	'thewire:widget:desc' => 'Display your latest wire posts',
	'thewire:num' => 'Number of posts to display',
	'thewire:moreposts' => 'More wire posts',

	/**
	 * Status messages
	 */
	'thewire:posted' => "Your message was successfully posted to the wire.",
	'thewire:deleted' => "The wire post was successfully deleted.",
	'thewire:blank' => "Sorry, you need to enter some text before we can post this.",
	'thewire:notfound' => "Sorry, we could not find the specified wire post.",
	'thewire:notdeleted' => "Sorry. We could not delete this wire post.",

	/**
	 * Notifications
	 */
	'thewire:notify:subject' => "New wire post",
	'thewire:notify:reply' => '%s responded to %s on the wire:',
	'thewire:notify:post' => '%s posted on the wire:',

);

add_translation("en", $english);
