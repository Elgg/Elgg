<?php

return [
	'actions' => [
		'friends/collections/edit' => [],
		'friends/collections/delete' => [],
		'friends/collections/remove_member' => [],
	],
	'routes' => [
		'add:access_collection:friends' => [
			'path' => '/friends/collections/add/{username?}',
			'resource' => 'friends/collections/add',
		],
		'edit:access_collection:friends' => [
			'path' => '/friends/collections/edit/{collection_id}',
			'resource' => 'friends/collections/edit',
			'requirements' => [
				'collection_id' => '\d+',
			],
		],
		'view:access_collection:friends' => [
			'path' => '/friends/collections/view/{collection_id}',
			'resource' => 'friends/collections/view',
			'requirements' => [
				'collection_id' => '\d+',
			],
		],
		'collection:access_collection:friends:owner' => [
			'path' => '/friends/collections/owner/{username?}',
			'resource' => 'friends/collections/owner',
		],
	],
];
