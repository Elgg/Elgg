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
			'path' => '/blog/owner/{username?}/{lower?}/{upper?}',
			'resource' => 'blog/owner',
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
		],
		'collection:object:blog:friends' => [
			'path' => '/blog/friends/{username?}/{lower?}/{upper?}',
			'resource' => 'blog/friends',
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
		],
		'collection:object:blog:archive' => [
			'path' => '/blog/archive/{username?}/{lower?}/{upper?}',
			'resource' => 'blog/owner',
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
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'edit:object:blog' => [
			'path' => '/blog/edit/{guid}/{revision?}',
			'resource' => 'blog/edit',
			'requirements' => [
				'revision' => '\d+',
			],
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'collection:object:blog:group' => [
			'path' => '/blog/group/{guid}/{subpage?}/{lower?}/{upper?}',
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
			'path' => '/blog/all/{lower?}/{upper?}',
			'resource' => 'blog/all',
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
		],
		'default:object:blog' => [
			'path' => '/blog',
			'resource' => 'blog/all',
		],
	],
	'widgets' => [
		'blog' => [
			'context' => ['profile', 'dashboard'],
		],
	],
];
