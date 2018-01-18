<?php

return [
	'routes' => [
		'tagcloud' => [
			'path' => '/tags',
			'resource' => 'tagcloud',
		],
	],
	'widgets' => [
		'tagcloud' => [
			'name' => elgg_echo('tagcloud:widget:title'),
			'description' => elgg_echo('tagcloud:widget:description'),
			'context' => ['profile', 'dashboard'],
		],
	],
];
