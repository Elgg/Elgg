<?php

use Elgg\Project\Paths;

$user = elgg_get_logged_in_user_entity();

// Test sending attachments through \ElggUser::notify()
$user->notify('useradd', $user, [
	'password' => 'test123',
	'attachments' => [
		[
			'filepath' => Paths::elgg() . 'README.md',
			'filename' => 'README.md',
			'type' => 'text/markdown',
		],
	],
]);

return elgg_ok_response('', elgg_echo('theme_sandbox:test_email:success', [$user->email]));
