<?php

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'User Validation by Email',
		'activate_on_install' => true,
	],
	'actions' => [
		'uservalidationbyemail/change_email' => [
			'access' => 'logged_out',
		],
		'uservalidationbyemail/resend_validation' => [
			'access' => 'admin',
		],
	],
	'routes' => [
		'account:validation:email:change' => [
			'path' => '/uservalidationbyemail/change_email',
			'resource' => 'uservalidationbyemail/change_email',
			'walled' => false,
			'middleware' => [
				\Elgg\Router\Middleware\LoggedOutGatekeeper::class,
			],
		],
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
	'events' => [
		'login:before' => [
			'user' => [
				'Elgg\UserValidationByEmail\User::preventLogin' => [],
			],
		],
	],
	'hooks' => [
		'register' => [
			'menu:admin:users:bulk' => [
				'Elgg\UserValidationByEmail\Menus\AdminUsersBulk::register' => [],
			],
			'menu:user:unvalidated' => [
				'Elgg\UserValidationByEmail\Menus\UserUnvalidated::register' => [],
			],
			'user' => [
				'Elgg\UserValidationByEmail\User::disableUserOnRegistration' => [],
			],
		],
		'response' => [
			'action:register' => [
				'Elgg\UserValidationByEmail\Response::redirectToEmailSent' => [],
			],
		],
	],
];
