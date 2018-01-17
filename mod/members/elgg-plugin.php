<?php

return [
	'routes' => [
		'collection:user:user' => [
			'path' => '/members',
			'resource' => 'members/newest',
		],
		'collection:user:user:alpha' => [
			'path' => '/members/alpha',
			'resource' => 'members/alpha',
		],
		'collection:user:user:newest' => [
			'path' => '/members/newest',
			'resource' => 'members/newest',
		],
		'collection:user:user:online' => [
			'path' => '/members/online',
			'resource' => 'members/online',
		],
		'collection:user:user:popular' => [
			'path' => '/members/popular',
			'resource' => 'members/popular',
		],
		'search:user:user' => [
			'path' => '/members/search',
			'resource' => 'members/search',
		],
	],
];
