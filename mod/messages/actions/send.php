<?php

	/**
	 * Elgg send a message action page
	 * 
	 * @package ElggMessages
	 */
	 
	 // Make sure we're logged in (send us to the front page if not)
		if (!isloggedin()) forward();

	// Get input data
		$title = strip_tags(get_input('title')); // message title
		$message_contents = get_input('message'); // the message
		$send_to = get_input('send_to'); // this is the user guid to whom the message is going to be sent
		$reply = get_input('reply',0); // this is the guid of the message replying to
		
	// Cache to the session to make form sticky
		$_SESSION['msg_to'] = $send_to;
		$_SESSION['msg_title'] = $title;
		$_SESSION['msg_contents'] = $message_contents;

		if (empty($send_to)) {
			register_error(elgg_echo("messages:user:blank"));
			forward("pg/messages/compose/");
		}
		
		$user = get_user($send_to);
		if (!$user) {
			register_error(elgg_echo("messages:user:nonexist"));
			forward("pg/messages/compose/");
		}

	// Make sure the message field, send to field and title are not blank
		if (empty($message_contents) || empty($title)) {
			register_error(elgg_echo("messages:blank"));
			forward("pg/messages/compose/");
		}
		
	// Otherwise, 'send' the message 
		$result = messages_send($title,$message_contents,$send_to,0,$reply);
			
	// Save 'send' the message
		if (!$result) {
			register_error(elgg_echo("messages:error"));
			forward("pg/messages/compose/");
		}

	// successful so uncache form values
		unset($_SESSION['msg_to']);
		unset($_SESSION['msg_title']);
		unset($_SESSION['msg_contents']);
			
	// Success message
		system_message(elgg_echo("messages:posted"));
	
	// Forward to the users inbox
		forward('pg/messages/inbox/' . get_loggedin_user()->username);

?>
