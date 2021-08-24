<?php

use Elgg\Friends\Actions\AddFriendController;
use Elgg\Router\Middleware\Gatekeeper;
use Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper;
use Elgg\Friends\Actions\RevokeFriendRequestController;
use Elgg\Friends\Actions\DeclineFriendRequestController;
use Elgg\Friends\Actions\AcceptFriendRequestController;

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'Friends',
		'activate_on_install' => true,
	],
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
				'Elgg\Friends\Relationships::removePendingFriendRequest' => [],
				'Elgg\Friends\Relationships::applyFriendNotificationsSettings' => [],
				'Elgg\Friends\Notifications::sendFriendNotification' => [],
			],
		],
		'delete' => [
			'relationship' => [
				'Elgg\Friends\Relationships::deleteFriendRelationship' => [],
				'Elgg\Friends\Relationships::deleteFriendNotificationSubscription' => [],
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
		'friends_of' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'hooks' => [
		'access:collections:write:subtypes' => [
			'user' => [
				'Elgg\Friends\Access::registerAccessCollectionType' => [],
			],
		],
		'entity:url' => [
			'object' => [
				'Elgg\Friends\Widgets::setWidgetUrl' => [],
			],
		],
		'register' => [
			'menu:filter:filter' => [
				'Elgg\Friends\Menus\Filter::registerFilterTabs' => ['priority' => 1],
			],
			'menu:filter:friends' => [
				'Elgg\Friends\Menus\Filter::addFriendRequestTabs' => [],
			],
			'menu:page' => [
				'Elgg\Friends\Menus\Page::register' => [],
			],
			'menu:relationship' => [
				'\Elgg\Friends\Menus\Relationship::addPendingFriendRequestItems' => [],
				'\Elgg\Friends\Menus\Relationship::addSentFriendRequestItems' => [],
			],
			'menu:title' => [
				'Elgg\Friends\Menus\Title::register' => [],
			],
			'menu:topbar' => [
				'Elgg\Friends\Menus\Topbar::register' => [],
			],
			'menu:user_hover' => [
				'Elgg\Friends\Menus\UserHover::register' => [],
			],
		],
	],
	'view_extensions' => [
		'notifications/settings/records' => [
			'notifications/settings/friends' => [],
		],
	],
];
