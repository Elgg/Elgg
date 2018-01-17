<?php

return [
	'routes' => [
		'default:search' => [
			'path' => '/search/{route_query?}',
			'resource' => 'search/index',
		],
	],
];
