<?php

return [
	'plugin' => [
		'name' => 'Tag Cloud',
	],
	'routes' => [
		'tagcloud' => [
			'path' => '/tags',
			'resource' => 'tagcloud',
		],
	],
	'widgets' => [
		'tagcloud' => [
			'context' => ['profile', 'dashboard'],
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'elgg/tagcloud.css' => [],
		],
		'theme_sandbox/components' => [
			'tagcloud/theme_sandbox/component' => [],
		],
	],
];
