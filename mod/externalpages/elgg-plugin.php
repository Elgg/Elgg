<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'about',
			'searchable' => false,
		],
		[
			'type' => 'object',
			'subtype' => 'terms',
			'searchable' => false,
		],
		[
			'type' => 'object',
			'subtype' => 'privacy',
			'searchable' => false,
		],
	],
	'routes' => [
		'view:object:about' => [
			'path' => '/about',
			'resource' => 'expages',
			'defaults' => [
				'expage' => 'about',
			],
		],
		'view:object:privacy' => [
			'path' => '/privacy',
			'resource' => 'expages',
			'defaults' => [
				'expage' => 'privacy',
			],
		],
		'view:object:terms' => [
			'path' => '/terms',
			'resource' => 'expages',
			'defaults' => [
				'expage' => 'terms',
			],
		],
	],
	'actions' => [
		'expages/edit' => [
			'access' => 'admin',
		],
	],
];
