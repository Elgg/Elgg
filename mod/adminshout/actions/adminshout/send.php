<?php
/**
 * Send email announcement action
 * 
 * @package ElggAdminShout
 */

$subject = get_input('subject');
$message = get_input('message');
$offset = max(get_input('offset'), 0);
$limit = max(get_input('limit'), 0);

if (!$subject || !$message) {
	forward(REFERER);
}

$options = array(
	'type' => 'user',
	'offset' => $offset,
	'limit' => $limit,
	'order_by'=> 'e.time_created ASC',
);
$total = elgg_get_entities(array_merge($options, array('count' => true)));
$users = elgg_get_entities($options);

foreach ($users as $user) {
	notify_user($user->guid, elgg_get_logged_in_user_guid(), $subject, $message, array(), 'email');
}

$sent = $offset + count($users);
echo "{\"sent\": $sent, \"total\": $total}";
