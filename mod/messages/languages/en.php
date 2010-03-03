<?php

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'messages' => "Messages",
            'messages:back' => "back to messages",
			'messages:user' => "Your inbox",
			'messages:sentMessages' => "Sent messages",
			'messages:posttitle' => "%s's messages: %s",
			'messages:inbox' => "Inbox",
			'messages:send' => "Send a message",
			'messages:sent' => "Sent messages",
			'messages:message' => "Message",
			'messages:title' => "Title",
			'messages:to' => "To",
            'messages:from' => "From",
			'messages:fly' => "Send",
			'messages:replying' => "Message replying to",
			'messages:inbox' => "Inbox",
			'messages:sendmessage' => "Send a message",
			'messages:compose' => "Compose a message",
			'messages:sentmessages' => "Sent messages",
			'messages:recent' => "Recent messages",
            'messages:original' => "Original message",
            'messages:yours' => "Your message",
            'messages:answer' => "Reply",
			'messages:toggle' => 'Toggle all',
			'messages:markread' => 'Mark read',
			
			'messages:new' => 'New message',
	
			'notification:method:site' => 'Site',
	
			'messages:error' => 'There was a problem saving your message. Please try again.',
	
			'item:object:messages' => 'Messages',
	
		/**
		 * Status messages
		 */
	
			'messages:posted' => "Your message was successfully sent.",
			'messages:deleted' => "Your messages were successfully deleted.",
			'messages:markedread' => "Your messages were successfully marked as read.",
	
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
			'messages:nomessages' => "There are no messages to display.",
			'messages:user:nonexist' => "We could not find the recipient in the user database.",
			'messages:user:blank' => "You did not select someone to send this to.",
	
	);
					
	add_translation("en",$english);

?>