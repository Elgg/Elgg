<?php

	/**
	 * Elgg send a message page
	 *
	 * @package ElggMessages
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// If we're not logged in, forward to the front page
		gatekeeper(); // if (!isloggedin()) forward();

	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = get_loggedin_user();
			set_page_owner($page_owner->getGUID());
		}

	// Get the users friends; this is used in the drop down to select who to send the message to
		$friends = get_loggedin_user()->getFriends('', 9999);

	// Set the page title
		$area2 = elgg_view_title(elgg_echo("messages:sendmessage"));

	// Get the send form
		$area2 .= elgg_view("messages/forms/message",array('friends' => $friends));

	// Format
		$body = elgg_view_layout("two_column_left_sidebar", '', $area2);

	// Draw page
		page_draw(sprintf(elgg_echo('messages:send'),$page_owner->name),$body);

?>