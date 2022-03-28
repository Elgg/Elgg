<?php
/**
 * Bulk unban users
 */

$user_guids = (array) get_input('user_guids');
if (empty($user_guids)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

/* @var $users \ElggBatch */
$users = elgg_get_entities([
	'type' => 'user',
	'guids' => $user_guids,
	'limit' => false,
	'batch' => true,
	'batch_inc_offset' => false,
	'metadata_name_value_pairs' => [
		'banned' => 'yes',
	],
]);

/* @var $user \ElggUser */
$count = 0;
foreach ($users as $user) {
	if ($user->unban()) {
		$count++;
	} else {
		$users->reportFailure();
	}
}

return elgg_ok_response('', elgg_echo('action:admin:user:bulk:unban', [$count]));
