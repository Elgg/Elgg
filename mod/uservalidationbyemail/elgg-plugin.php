<?php

return [
	'actions' => [
		'uservalidationbyemail/resend_validation' => [
			'access' => 'admin',
		],
	],
	'routes' => [
		'account:validation:email:confirm' => [
			'path' => '/uservalidationbyemail/confirm',
			'resource' => 'uservalidationbyemail/confirm',
			'walled' => false,
		],
		'account:validation:email:sent' => [
			'path' => '/uservalidationbyemail/emailsent',
			'resource' => 'uservalidationbyemail/emailsent',
			'walled' => false,
			'middleware' => [
				\Elgg\Router\Middleware\LoggedOutGatekeeper::class,
			],
		],
	],
];
