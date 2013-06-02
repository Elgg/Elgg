<?php
/**
 * Messages helper functions
 *
 * @package ElggMessages
 */

/**
 * Prepare the compose form variables
 *
 * @return array
 */
function messages_prepare_form_vars($recipient_guid = 0) {

	$recipient_username = '';
	$recipient = get_entity($recipient_guid);
	if (elgg_instanceof($recipient, 'user')) {
		$recipient_username = $recipient->username;
	}

	// input names => defaults
	$values = array(
		'subject' => '',
		'body' => '',
		'recipient_username' => $recipient_username,
	);

	if (elgg_is_sticky_form('messages')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('messages', $field);
		}
	}

	elgg_clear_sticky_form('messages');

	return $values;
}

