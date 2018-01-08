<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'blog',
			'class' => 'ElggBlog',
			'searchable' => true,
		],
	],
	'actions' => [
		'blog/save' => [],
		'blog/auto_save_revision' => [],
		'blog/delete' => [],
	],
	'routes' => [
		'collection:object:blog:owner' => [
			'path' => '/blog/owner/{username?}',
			'resource' => 'blog/owner',
		],
		'collection:object:blog:friends' => [
			'path' => '/blog/friends/{username?}',
			'resource' => 'blog/friends',
		],
		'collection:object:blog:archive' => [
			'path' => '/blog/archive/{username?}/{lower?}/{upper?}',
			'resource' => 'blog/archive',
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
		],
		'view:object:blog' => [
			'path' => '/blog/view/{guid}/{title?}',
			'resource' => 'blog/view',
		],
		'add:object:blog' => [
			'path' => '/blog/add/{guid?}',
			'resource' => 'blog/add',
		],
		'edit:object:blog' => [
			'path' => '/blog/edit/{guid}/{revision?}',
			'resource' => 'blog/edit',
			'requirements' => [
				'revision' => '\d+',
			],
		],
		'collection:object:blog:group' => [
			'path' => '/blog/group/{group_guid}/{subpage?}/{lower?}/{upper?}',
			'resource' => 'blog/group',
			'defaults' => [
				'subpage' => 'all',
			],
			'requirements' => [
				'subpage' => 'all|archive',
				'lower' => '\d+',
				'upper' => '\d+',
			],
		],
		'collection:object:blog:all' => [
			'path' => '/blog/all',
			'resource' => 'blog/all',
		],
		'collection:object:blog' => [
			'path' => '/blog',
			'resource' => 'blog/all',
		],
	],
	'widgets' => [
		'blog' => [
			'description' => elgg_echo('blog:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
