<?php

return [
	'plugin' => [
		'name' => 'Invite Friends',
		'activate_on_install' => true,
	],
	'actions' => [
		'friends/invite' => [],
	],
	'routes' => [
		'default:user:user:invite' => [
			'path' => '/friends/{username}/invite',
			'resource' => 'friends/invite',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'hooks' => [
		'register' => [
			'menu:page' => [
				'Elgg\InviteFriends\Menus\Page::register' => [],
			],
			'user' => [
				'Elgg\InviteFriends\Users::addFriendsOnRegister' => [],
			],
		],
	],
	'events' => [
		'validate:after' => [
			'user' => [
				'Elgg\InviteFriends\Users::addFriendsAfterValidation' => [],
			],
		],
	],
];
