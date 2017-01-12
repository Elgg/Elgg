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
		'blog_owner' => [
			'path' => '/blog/owner/{username?}',
			'resource' => 'blog/owner',
		],
		'blog_friends' => [
			'path' => '/blog/friends/{username?}',
			'resource' => 'blog/friends',
		],
		'blog_archive' => [
			'path' => '/blog/archive/{username?}/{lower?}/{upper?}',
			'resource' => 'blog/archive',
			'requirements' => [
				'lower' => '\d+',
				'upper' => '\d+',
			],
		],
		'blog_view' => [
			'path' => '/blog/view/{guid}/{title?}',
			'resource' => 'blog/view',
		],
		'blog_add' => [
			'path' => '/blog/add/{guid?}',
			'resource' => 'blog/add',
		],
		'blog_edit' => [
			'path' => '/blog/edit/{guid}/{revision?}',
			'resource' => 'blog/edit',
			'requirements' => [
				'revision' => '\d+',
			],
		],
		'blog_group' => [
			'path' => '/blog/group/{group_guid}/{subpage?}/{lower?}/{upper?}',
			'resource' => 'blog/group',
			'defaults' => [
				'subpage' => 'all',
				'lower' => '',
				'upper' => '',
			],
			'requirements' => [
				'subpage' => 'all|archive',
				'lower' => '\d+',
				'upper' => '\d+',
			],
		],
		'blog_all' => [
			'path' => '/blog/all',
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
