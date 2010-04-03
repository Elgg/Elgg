<?php

	/**
	 * Elgg send a message action page
	 * 
	 * @package ElggMessages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
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
			forward("mod/messages/send.php");
		}
		
		$user = get_user($send_to);
		if (!$user) {
			register_error(elgg_echo("messages:user:nonexist"));
			forward("mod/messages/send.php");
		}

	// Make sure the message field, send to field and title are not blank
		if (empty($message_contents) || empty($title)) {
			register_error(elgg_echo("messages:blank"));
			forward("mod/messages/send.php");
		}
		
	// Otherwise, 'send' the message 
		$result = messages_send($title,$message_contents,$send_to,0,$reply);
			
	// Save 'send' the message
		if (!$result) {
			register_error(elgg_echo("messages:error"));
			forward("mod/messages/send.php");
		}

	// successful so uncache form values
		unset($_SESSION['msg_to']);
		unset($_SESSION['msg_title']);
		unset($_SESSION['msg_contents']);
			
	// Success message
		system_message(elgg_echo("messages:posted"));
	
	// Forward to the users inbox
		forward('pg/messages/' . get_loggedin_user()->username);

?>
