<?php
/**
* Elgg send a message action page
* 
* @package ElggMessages
*/

$english = array(
	/**
	* Menu items and titles
	*/

	'messages' => "Messages",
	'messages:back' => "back to messages",
	'messages:user' => "%s's inbox",
	'messages:posttitle' => "%s's messages: %s",
	'messages:inbox' => "Inbox",
	'messages:send' => "Send",
	'messages:sent' => "Sent",
	'messages:message' => "Message",
	'messages:title' => "Subject",
	'messages:to' => "To",
	'messages:from' => "From",
	'messages:fly' => "Send",
	'messages:replying' => "Message replying to",
	'messages:inbox' => "Inbox",
	'messages:sendmessage' => "Send a message",
	'messages:compose' => "Compose a message",
	'messages:add' => "Compose a message",
	'messages:sentmessages' => "Sent messages",
	'messages:recent' => "Recent messages",
	'messages:original' => "Original message",
	'messages:yours' => "Your message",
	'messages:answer' => "Reply",
	'messages:toggle' => 'Toggle all',
	'messages:markread' => 'Mark read',
	'messages:recipient' => 'Choose a recipient&hellip;',
	'messages:to_user' => 'To: %s',

	'messages:new' => 'New message',

	'notification:method:site' => 'Messages',

	'messages:error' => 'There was a problem saving your message. Please try again.',

	'item:object:messages' => 'Messages',

	/**
	* Status messages
	*/

	'messages:posted' => "Your message was successfully sent.",
	'messages:success:delete:single' => 'Message was deleted',
	'messages:success:delete' => 'Messages deleted',
	'messages:success:read' => 'Messages marked as read',
	'messages:error:messages_not_selected' => 'No messages selected',
	'messages:error:delete:single' => 'Unable to delete the message',

	/**
	* Email messages
	*/

	'messages:email:subject' => 'You have a new message!',
	'messages:email:body' => "You have a new message from %s. It reads:


	%s


	To view your messages, click here:

	%s

	To send %s a message, click here:

	%s

	You cannot reply to this email.",

	/**
	* Error messages
	*/

	'messages:blank' => "Sorry; you need to actually put something in the message body before we can save it.",
	'messages:notfound' => "Sorry; we could not find the specified message.",
	'messages:notdeleted' => "Sorry; we could not delete this message.",
	'messages:nopermission' => "You do not have permission to alter that message.",
	'messages:nomessages' => "There are no messages.",
	'messages:user:nonexist' => "We could not find the recipient in the user database.",
	'messages:user:blank' => "You did not select someone to send this to.",

	'messages:deleted_sender' => 'Deleted user',

);
		
add_translation("en", $english);