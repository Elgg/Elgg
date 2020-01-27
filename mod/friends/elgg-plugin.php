<?php

use Elgg\Friends\Actions\AddFriendController;
use Elgg\Router\Middleware\Gatekeeper;
use Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper;
use Elgg\Friends\Actions\RevokeFriendRequestController;
use Elgg\Friends\Actions\DeclineFriendRequestController;
use Elgg\Friends\Actions\AcceptFriendRequestController;

return [
	'actions' => [
		'friends/add' => [
			'controller' => AddFriendController::class,
		],
		'friends/remove' => [],
		'friends/request/revoke' => [
			'controller' => RevokeFriendRequestController::class,
		],
		'friends/request/decline' => [
			'controller' => DeclineFriendRequestController::class,
		],
		'friends/request/accept' => [
			'controller' => AcceptFriendRequestController::class,
		],
	],
	'events' => [
		'create' => [
			'relationship' => [
				'\Elgg\Friends\Relationships::createFriendRelationship' => [],
			],
		],
	],
	'routes' => [
		'collection:friends:owner' => [
			'path' => '/friends/{username}',
			'resource' => 'friends/index',
		],
		'collection:friends_of:owner' => [
			'path' => '/friendsof/{username}',
			'resource' => 'friends/of',
		],
		'collection:relationship:friendrequest:pending' => [
			'path' => '/friends/{username}/pending',
			'resource' => 'friends/pending',
			'middleware' => [
				Gatekeeper::class,
				UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'collection:relationship:friendrequest:sent' => [
			'path' => '/friends/{username}/sent',
			'resource' => 'friends/sent',
			'middleware' => [
				Gatekeeper::class,
				UserPageOwnerCanEditGatekeeper::class,
			],
		],
	],
	'settings' => [
		'friend_request' => 0,
	],
	'widgets' => [
		'friends' => [
			'context' => ['profile', 'dashboard'],
		],
	],
];
