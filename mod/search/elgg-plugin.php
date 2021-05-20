<?php

return [
	'plugin' => [
		'name' => 'Search',
		'activate_on_install' => true,
	],
	'routes' => [
		'default:search' => [
			'path' => '/search/{route_query?}',
			'resource' => 'search/index',
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'search/search.css' => [],
		],
	],
	'hooks' => [
		'robots.txt' => [
			'site' => [
				'Elgg\Search\Site::preventSearchIndexing' => [],
			],
		],
		'view_vars' => [
			'output/tag' => [
				'Elgg\Search\Views::setSearchHref' => [],
			],
		],
	],
];
