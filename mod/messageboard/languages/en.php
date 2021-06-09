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

	'messageboard:board' => "Message board",
	'messageboard:none' => "There is nothing on this message board yet",
	'messageboard:num_display' => "Number of messages to display",
	'messageboard:owner' => '%s\'s message board',
	'messageboard:owner_history' => '%s\'s posts on %s\'s message board',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s posted on %s's message board",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "You successfully posted on the message board.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'You have a new message board comment!',
	'messageboard:email:body' => "You have a new message board comment from %s.

It reads:

%s

To view your message board comments, click here:
%s

To view %s's profile, click here:
%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Sorry; you need to actually put something in the message area before we can save it.",

	'messageboard:failure' => "An unexpected error occurred when adding your message. Please try again.",

	'widgets:messageboard:name' => "Message board",
	'widgets:messageboard:description' => "This is a message board that you can put on your profile where other users can comment.",
);
