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
		'default' => [
			'search-highlight-color' => '#BBDAF7',
			'search-highlight-color-1' => '#BBDAF7',
			'search-highlight-color-2' => '#A0FFFF',
			'search-highlight-color-3' => '#FDFFC3',
			'search-highlight-color-4' => '#CCCCCC',
			'search-highlight-color-5' => '#08A7E7',
		],
		'dark' => [
			'search-highlight-color' => '#2d3133',
			'search-highlight-color-1' => '#2d3133',
			'search-highlight-color-2' => '#006c6c',
			'search-highlight-color-3' => '#404100',
			'search-highlight-color-4' => '#35393b',
			'search-highlight-color-5' => '#003f5a',
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'search/search.css' => [],
		],
	],
];
