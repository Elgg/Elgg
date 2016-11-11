<?php

return [
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
