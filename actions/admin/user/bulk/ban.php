<?php
/**
 * Bulk ban users
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
		'banned' => 'no',
	],
]);

/* @var $user \ElggUser */
$count = 0;
foreach ($users as $user) {
	if ($user->guid === elgg_get_logged_in_user_guid()) {
		// can't ban yourself
		$users->reportFailure();
		continue;
	}
	
	if ($user->ban('admin')) {
		if (elgg_get_config('security_notify_user_ban')) {
			// this can't be handled by the delayed notification system as it won't send notifications to banned users
			$user->notify('ban', $user);
		}
		
		$count++;
	} else {
		$users->reportFailure();
	}
}

return elgg_ok_response('', elgg_echo('action:admin:user:bulk:ban', [$count]));
