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

	$recipients = array();
	$recipient = get_user($recipient_guid);
	if (!empty($recipient)) {
		$recipients[] = $recipient->getGUID();
	}

	// input names => defaults
	$values = array(
		'subject' => '',
		'body' => '',
		'recipients' => $recipients,
	);

	if (elgg_is_sticky_form('messages')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('messages', $field);
		}
	}

	elgg_clear_sticky_form('messages');

	return $values;
}

