<?php

return [
	'actions' => [
		'friends/invite' => [],
	],
	'routes' => [
		'default:user:user:invite' => [
			'path' => '/friends/invite',
			'resource' => 'friends/invite',
		],
	],
];
