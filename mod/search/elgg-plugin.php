<?php

return [
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
		'search:format' => [
			'entity' => [
				\Elgg\Search\FormatComentEntityHook::class => [],
			],
		],
		'view_vars' => [
			'output/tag' => [
				'Elgg\Search\Views::setSearchHref' => [],
			],
		],
	],
];
