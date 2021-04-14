<?php

use Elgg\Project\Paths;

$user = elgg_get_logged_in_user_entity();
$site = elgg_get_site_entity();

$subject = elgg_echo('useradd:subject');
$plain_message = elgg_echo('useradd:body', [
	$site->getDisplayName(),
	$site->getURL(),
	$user->username,
	'test123',
]);

// Test sending attachments through notify_user()
$params = [
	'attachments' => [
		[
			'filepath' => Paths::elgg() . 'README.md',
			'filename' => 'README.md',
			'type' => 'text/markdown',
		],
	],
];

notify_user($user->guid, $site->guid, $subject, $plain_message, $params, ['email']);

return elgg_ok_response('', elgg_echo('theme_sandbox:test_email:success', [$user->email]));
