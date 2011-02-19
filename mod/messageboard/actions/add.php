<?php
/**
 * Elgg Message board: add message action
 *
 * @package ElggMessageBoard
 */

$message_content = get_input('message_content');
$owner_guid = get_input("owner_guid");
$owner = get_entity($owner_guid);

if ($owner && !empty($message_content)) {
	if (messageboard_add(elgg_get_logged_in_user_entity(), $owner, $message_content, $owner->access_id)) {
		system_message(elgg_echo("messageboard:posted"));

		// push the newest content out if using ajax
		$is_ajax = array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		if ($is_ajax) {
			$contents = $owner->getAnnotations('messageboard', 1, 0, 'desc');
			$post = elgg_view("messageboard/messageboard_content", array('annotation' => $contents[0]));
			echo json_encode(array('post' => $post));
		}

	} else {
		register_error(elgg_echo("messageboard:failure"));
	}

} else {
	register_error(elgg_echo("messageboard:blank"));
}

forward(REFERER);
