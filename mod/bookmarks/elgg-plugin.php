<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'bookmarks',
			'class' => 'ElggBookmark',
			'searchable' => true,
		],
	],
	'actions' => [
		'bookmarks/save' => [],
	],
	'routes' => [
		'default:object:bookmarks' => [
			'path' => '/bookmarks',
			'resource' => 'bookmarks/all',
		],
		'collection:object:bookmarks:all' => [
			'path' => '/bookmarks/all',
			'resource' => 'bookmarks/all',
		],
		'collection:object:bookmarks:owner' => [
			'path' => '/bookmarks/owner/{username}',
			'resource' => 'bookmarks/owner',
		],
		'collection:object:bookmarks:friends' => [
			'path' => '/bookmarks/friends/{username}',
			'resource' => 'bookmarks/friends',
		],
		'collection:object:bookmarks:group' => [
			'path' => '/bookmarks/group/{guid}/{subpage?}',
			'resource' => 'bookmarks/group',
			'defaults' => [
				'subpage' => 'all',
			],
		],
		'add:object:bookmarks' => [
			'path' => '/bookmarks/add/{guid}',
			'resource' => 'bookmarks/add',
		],
		'view:object:bookmarks' => [
			'path' => '/bookmarks/view/{guid}/{title?}',
			'resource' => 'bookmarks/view',
		],
		'edit:object:bookmarks' => [
			'path' => '/bookmarks/edit/{guid}',
			'resource' => 'bookmarks/edit',
		],
		'bookmarklet:object:bookmarks' => [
			'path' => '/bookmarks/bookmarklet/{container_guid}',
			'resource' => 'bookmarks/bookmarklet',
			'requirements' => [
				'container_guid' => '\d+',
			],
		],
	],
	'widgets' => [
		'bookmarks' => [
			'description' => elgg_echo('widgets:bookmarks:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
