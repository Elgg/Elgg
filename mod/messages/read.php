<?php
/**
* Elgg read a message page
*
* @package ElggMessages
*/

// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// If we're not logged in, forward to the front page
gatekeeper();

$page_owner = get_loggedin_user();
$mbox_type = get_input('type', 'inbox');

// Get the full message object to read
$message = get_entity(get_input("message"));

// If no message, must have been deleted, send user to inbox/sent mail
if (!$message) {
	if ($mbox_type == 'sent') {
		forward("mod/messages/sent.php");
	} else {
		forward("pg/messages/{$page_owner->username}");
	}
}

// If the message is being read from the inbox, mark it as read, otherwise don't.
// This stops a user who checks out a message they have sent having it being marked
// as read for the recipient
if($mbox_type != "sent"){
	// Mark the message as being read now
	if ($message->getSubtype() == "messages") {
		//set the message metadata to 1 which equals read
		$message->readYet = 1;
	}
}

set_page_owner($page_owner->getGUID());

// Display it
$content = elgg_view("messages/messages",array(
									'entity' => $message,
									'entity_owner' => $page_owner,
									'full' => true
									));

$sidebar = elgg_view("messages/menu_options");

$body = elgg_view_layout("one_column_with_sidebar", $content, $sidebar);

// Display page
page_draw(sprintf(elgg_echo('messages:message')),$body);