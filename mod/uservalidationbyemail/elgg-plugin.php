<?php

use Elgg\UserValidationByEmail\Upgrades\TrackValidationStatus;

return [
	'actions' => [
		'uservalidationbyemail/resend_validation' => [
			'access' => 'admin',
		],
	],
	'routes' => [
		'account:validation:email:confirm' => [
			'path' => '/uservalidationbyemail/confirm',
			'controller' => \Elgg\UserValidationByEmail\ConfirmController::class,
			'walled' => false,
			'middleware' => [
				\Elgg\Router\Middleware\SignedRequestGatekeeper::class,
			],
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
	'upgrades' => [
		TrackValidationStatus::class,
	],
];
