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
	'events' => [
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
	'theme' => [
		'search-highlight-color' => '#BBDAF7',
		'search-highlight-color-1' => '#BBDAF7',
		'search-highlight-color-2' => '#A0FFFF',
		'search-highlight-color-3' => '#FDFFC3',
		'search-highlight-color-4' => '#CCCCCC',
		'search-highlight-color-5' => '#08A7E7',
	],
	'view_extensions' => [
		'elgg.css' => [
			'search/search.css' => [],
		],
	],
];
