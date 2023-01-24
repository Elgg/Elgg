<?php

use Elgg\Groups\Forms\PrepareFields;
use Elgg\Groups\Middleware\LimitedGroupCreation;

require_once(__DIR__ . '/lib/functions.php');

$membership = __DIR__ . '/actions/groups/membership/';

return [
	'plugin' => [
		'name' => 'Groups',
		'activate_on_install' => true,
	],
	'settings' => [
		'hidden_groups' => 'no',
		'limited_groups' => 'no',
	],
	'entities' => [
		[
			'type' => 'group',
			'subtype' => 'group',
			'capabilities' => [
				'commentable' => false,
				'searchable' => true,
				'likable' => true,
			],
		],
	],
	'actions' => [
		'groups/edit' => [],
		'groups/featured' => [
			'access' => 'admin',
		],
		
		// membership actions
		'groups/invite' => [
			'filename' => "{$membership}invite.php",
		],
		'groups/join' => [
			'filename' => "{$membership}join.php",
		],
		'groups/leave' => [
			'filename' => "{$membership}leave.php",
		],
		'groups/remove' => [
			'filename' => "{$membership}remove.php",
		],
		'groups/killrequest' => [
			'filename' => "{$membership}delete_request.php",
		],
		'groups/killinvitation' => [
			'filename' => "{$membership}delete_invite.php",
		],
		'groups/addtogroup' => [
			'filename' => "{$membership}add.php",
		],
	],
	'routes' => [
		'default:group:group' => [
			'path' => '/groups',
			'resource' => 'groups/all',
		],
		'collection:group:group:all' => [
			'path' => '/groups/all',
			'resource' => 'groups/all',
		],
		'collection:group:group:owner' => [
			'path' => '/groups/owner/{username}',
			'resource' => 'groups/owner',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:group:group:member' => [
			'path' => '/groups/member/{username}',
			'resource' => 'groups/member',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:group:group:invitations' => [
			'path' => '/groups/invitations/{username}',
			'resource' => 'groups/invitations',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
		'collection:group:group:search' => [
			'path' => '/groups/search',
			'resource' => 'groups/search',
		],
		'collection:user:user:group_members' => [
			'path' => '/groups/members/{guid}',
			'resource' => 'groups/members',
			'middleware' => [
				\Elgg\Router\Middleware\GroupPageOwnerGatekeeper::class,
			],
		],
		'collection:user:user:group_invites' => [
			'path' => '/groups/invites/{guid}',
			'resource' => 'groups/invites',
			'middleware' => [
				\Elgg\Router\Middleware\GroupPageOwnerCanEditGatekeeper::class,
			],
		],
		'add:group:group' => [
			'path' => '/groups/add/{guid}',
			'resource' => 'groups/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
				LimitedGroupCreation::class,
			],
		],
		'view:group:group' => [
			'path' => '/groups/profile/{guid}/{title?}',
			'resource' => 'groups/profile',
			'middleware' => [
				\Elgg\Router\Middleware\GroupPageOwnerGatekeeper::class,
			],
		],
		'edit:group:group' => [
			'path' => '/groups/edit/{guid}',
			'resource' => 'groups/edit',
			'middleware' => [
				\Elgg\Router\Middleware\GroupPageOwnerCanEditGatekeeper::class,
			],
		],
		'invite:group:group' => [
			'path' => '/groups/invite/{guid}',
			'resource' => 'groups/invite',
			'middleware' => [
				\Elgg\Router\Middleware\GroupPageOwnerCanEditGatekeeper::class,
			],
		],
		'requests:group:group' => [
			'path' => '/groups/requests/{guid}',
			'resource' => 'groups/requests',
			'middleware' => [
				\Elgg\Router\Middleware\GroupPageOwnerCanEditGatekeeper::class,
			],
		],
		'settings:notification:groups' => [
			'path' => '/settings/notifications/groups/{username}',
			'resource' => 'settings/notifications/groups',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
	],
	'widgets' => [
		'a_users_groups' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'groups/groups.css' => [],
		],
		'notifications/settings/records' => [
			'notifications/settings/group_join' => [],
		],
	],
	'events' => [
		'access:collections:write' => [
			'all' => [
				'Elgg\Groups\Access::getWriteAccess' => ['priority' => 600],
			],
		],
		'access_collection:name' => [
			'access_collection' => [
				'Elgg\Groups\Access::getAccessCollectionName' => [],
			],
		],
		'create' => [
			'relationship' => [
				'Elgg\Groups\Relationships::applyGroupNotificationSettings' => [],
			],
		],
		'create:after' => [
			'group' => [
				'Elgg\Groups\Group::createAccessCollection' => [],
				\Elgg\Notifications\CreateContentEventHandler::class => [],
			],
		],
		'default' => [
			'access' => [
				'Elgg\Groups\Access::getDefaultAccess' => [],
				'Elgg\Groups\Access::overrideDefaultAccess' => [],
			],
		],
		'delete' => [
			'relationship' => [
				'Elgg\Groups\Relationships::removeGroupNotificationSubscriptions' => [],
			],
		],
		'fields' => [
			'group:group' => [
				\Elgg\Groups\FieldsHandler::class => [],
			],
		],
		'form:prepare:fields' => [
			'groups/edit' => [
				PrepareFields::class => [],
			],
		],
		'gatekeeper' => [
			'group:group' => [
				'Elgg\Groups\Access::allowProfilePage' => [],
			],
		],
		'join' => [
			'group' => [
				'Elgg\Groups\Group::joinGroup' => [],
			],
		],
		'leave' => [
			'group' => [
				'Elgg\Groups\Group::leaveGroup' => [],
			],
		],
		'register' => [
			'menu:entity:group:group' => [
				'Elgg\Groups\Menus\Entity::register' => [],
				'Elgg\Groups\Menus\Entity::registerFeature' => [],
			],
			'menu:filter:groups/all' => [
				'Elgg\Groups\Menus\Filter::registerGroupsAll' => [],
				'Elgg\Menus\FilterSortItems::registerTimeCreatedSorting' => [],
				'Elgg\Menus\FilterSortItems::registerNameSorting' => [],
				'Elgg\Menus\FilterSortItems::registerSortingDropdown' => ['priority' => 9999],
			],
			'menu:filter:settings/notifications' => [
				'Elgg\Groups\Menus\Filter::registerNotificationSettings' => [],
			],
			'menu:filter:groups/members' => [
				'Elgg\Groups\Menus\Filter::registerGroupsMembers' => [],
				'Elgg\Groups\Menus\FilterSortItems::registerMembershipSorting' => [],
				'Elgg\Menus\FilterSortItems::registerNameSorting' => [],
				'Elgg\Menus\FilterSortItems::registerSortingDropdown' => ['priority' => 9999],
			],
			'menu:page' => [
				'Elgg\Groups\Menus\Page::register' => [],
				'Elgg\Groups\Menus\Page::registerGroupProfile' => [],
			],
			'menu:relationship' => [
				'Elgg\Groups\Menus\Relationship::registerInvitedItems' => [],
				'Elgg\Groups\Menus\Relationship::registerMembershipRequestItems' => [],
				'Elgg\Groups\Menus\Relationship::registerRemoveUser' => [],
			],
			'menu:site' => [
				'Elgg\Groups\Menus\Site::register' => [],
			],
			'menu:title' => [
				'Elgg\Groups\Menus\Title::register' => [],
			],
			'menu:topbar' => [
				'Elgg\Groups\Menus\Topbar::register' => [],
			],
		],
		'search:fields' => [
			'group' => [
				'Elgg\Search\GroupSearchProfileFieldsHandler' => [],
			],
		],
		'update:after' => [
			'group' => [
				'Elgg\Groups\Group::updateGroup' => [],
			],
		],
	],
];
