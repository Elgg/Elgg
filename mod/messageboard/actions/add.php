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
	$result = messageboard_add(elgg_get_logged_in_user_entity(), $owner, $message_content, $owner->access_id);

	if ($result) {
		system_message(elgg_echo("messageboard:posted"));

		// push the newest content out if using ajax
		$is_ajax = array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		if ($is_ajax) {
			// always return the entity with the full ul and li
			// this is parsed out as needed by js.
			// if this is the only post we need to return the entire ul
			$options = array(
				'annotations_name' => 'messageboard',
				'guid' => $owner->getGUID(),
				'limit' => $num_display,
				'pagination' => false,
				'reverse_order_by' => true,
				'limit' => 1
			);

			$output = elgg_list_annotations($options);
			echo json_encode(array('post' => $output));
		}

	} else {
		register_error(elgg_echo("messageboard:failure"));
	}

} else {
	register_error(elgg_echo("messageboard:blank"));
}

forward(REFERER);
