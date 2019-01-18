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
			'context' => ['profile', 'dashboard'],
		],
	],
];
