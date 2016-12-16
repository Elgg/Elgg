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
	'widgets' => [
		'blog' => [
			'description' => elgg_echo('blog:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
