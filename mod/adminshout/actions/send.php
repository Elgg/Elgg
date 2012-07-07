<?php
/**
 * Send email announcement action
 */

$subject = get_input('subject');
$message = get_input('message');
if (!$subject || !$message) {
	forward(REFERER);
}

$options = array(
	'type' => 'user',
	'limit' => 0,
);
$batch = new ElggBatch('elgg_get_entities', $options);
set_time_limit(0);
foreach ($batch as $user) {
	notify_user($user->guid, elgg_get_logged_in_user_guid(), $subject, $message, array(), 'email');
}

system_message(elgg_echo('adminshout:success'));
