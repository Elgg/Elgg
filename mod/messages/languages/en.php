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

	'messages' => "Messages",
	'messages:unreadcount' => "%s unread",
	'messages:user' => "%s's inbox",
	'messages:inbox' => "Inbox",
	'messages:sent' => "Sent",
	'messages:message' => "Message",
	'messages:title' => "Subject",
	'messages:to:help' => "Write recipient's username here.",
	'messages:inbox' => "Inbox",
	'messages:sendmessage' => "Send a message",
	'messages:add' => "Compose a message",
	'messages:sentmessages' => "Sent messages",
	'messages:toggle' => 'Toggle all',
	'messages:markread' => 'Mark read',

	'notification:method:site' => 'Site',

	'messages:error' => 'There was a problem saving your message. Please try again.',

	'item:object:messages' => 'Message',
	'collection:object:messages' => 'Messages',

	/**
	* Status messages
	*/

	'messages:posted' => "Your message was successfully sent.",
	'messages:success:delete' => 'Messages deleted',
	'messages:success:read' => 'Messages marked as read',
	'messages:error:messages_not_selected' => 'No messages selected',

	/**
	* Email messages
	*/

	'messages:email:subject' => 'You have a new message!',
	'messages:email:body' => "You have a new message from %s.

It reads:

%s

To view your messages, click here:
%s

To send %s a message, click here:
%s",

	/**
	* Error messages
	*/

	'messages:blank' => "Sorry; you need to actually put something in the message body before we can save it.",
	'messages:nomessages' => "There are no messages.",
	'messages:user:nonexist' => "We could not find the recipient in the user database.",
	'messages:user:blank' => "You did not select someone to send this to.",
	'messages:user:self' => "You cannot send a message to yourself.",
	'messages:user:notfriend' => "You cannot send a message to a user who is not your friend.",

	'messages:deleted_sender' => 'Deleted user',
	
	/**
	* Settings
	*/
	'messages:settings:friends_only:label' => 'Messages can only be sent to friends',
	'messages:settings:friends_only:help' => 'User will not be able to send a message if the recipient is not his friend',

);
