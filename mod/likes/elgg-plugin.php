<?php

return [
	'actions' => [
		'likes/add' => [],
		'likes/delete' => [],
	],
	'events' => [
		'delete' => [
			'group' => [
				'\Elgg\Likes\Delete::deleteLikes' => [],
			],
			'object' => [
				'\Elgg\Likes\Delete::deleteLikes' => [],
			],
			'site' => [
				'\Elgg\Likes\Delete::deleteLikes' => [],
			],
			'user' => [
				'\Elgg\Likes\Delete::deleteLikes' => [],
			],
		],
	],
	'upgrades' => [
		'\Elgg\Likes\Upgrades\PublicLikesAnnotations'
	],
];
