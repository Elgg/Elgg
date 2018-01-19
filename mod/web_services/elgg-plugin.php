<?php

return [
	'routes' => [
		'default:services' => [
			'path' => '/services/{segments}',
			'handler' => 'ws_page_handler',
			'defaults' => [
				'segments' => '',
			],
			'requirements' => [
				'segments' => '.+',
			],
		],
	],
];
