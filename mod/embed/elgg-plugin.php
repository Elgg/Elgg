<?php

return [
	'routes' => [
		'default:embed' => [
			'path' => '/embed/{tab?}',
			'resource' => 'embed/embed',
			'requirements' => [
				'tab' => '\w+',
			],
		],
	],
];
