<?php

/**
 * Elgg Message board
 * This plugin allows users and groups to attach a message board to their profile for other users
 * to post comments.
 * 
 * @package ElggMessageBoard
 */

/**
 * MessageBoard initialisation
 */
function messageboard_init() {

	// Extend system CSS with our own styles, which are defined in the messageboard/css view
	elgg_extend_view('css', 'messageboard/css');

	// Register a page handler, so we can have nice URLs
	register_page_handler('messageboard', 'messageboard_page_handler');

	// add a messageboard widget - only for profile
	add_widget_type('messageboard', elgg_echo("messageboard:board"), elgg_echo("messageboard:desc"), "profile");
}

/**
 * Messageboard page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function messageboard_page_handler($page) {

	global $CONFIG;

	// The username should be the first array entry
	if (isset($page[0])) {
		set_input('username', $page[0]);
	}
	
	// Include the standard messageboard index
	include($CONFIG->pluginspath . "messageboard/index.php");
}

/**
 * Add messageboard post
 *
 * @param ElggUser $poster User posting the message
 * @param ElggUser $owner User who owns the message board
 * @param string $message The posted message
 * @param int $access_id Access level (see defines in elgglib.php)
 * @return bool
 */
function messageboard_add($poster, $owner, $message, $access_id = ACCESS_PUBLIC) {
	global $CONFIG;
	
	$result = $owner->annotate('messageboard', $message, $access_id, $poster->guid);
	if (!$result) {
		return FALSE;
	}

	add_to_river('river/object/messageboard/create',
				'messageboard',
				$poster->guid,
				$owner->guid,
				$access_id,
				0,
				$result);

	// only send notification if not self
	if ($poster->guid != $owner->guid) {
		$subject = elgg_echo('messageboard:email:subject');
		$body = sprintf(
						elgg_echo('messageboard:email:body'),
						$poster->name,
						$message,
						$CONFIG->wwwroot . "pg/messageboard/" . $owner->username,
						$poster->name,
						$poster->getURL()
						);

		notify_user($owner->guid, $poster->guid, $subject, $body);
	}

	return TRUE;
}


// Register initialisation callback
register_elgg_event_handler('init', 'system', 'messageboard_init');

// Register actions
register_action("messageboard/add", FALSE, $CONFIG->pluginspath . "messageboard/actions/add.php");
register_action("messageboard/delete", FALSE, $CONFIG->pluginspath . "messageboard/actions/delete.php");
