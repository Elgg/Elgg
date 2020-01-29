<?php

return [
	'actions' => [
		'likes/add' => [],
		'likes/delete' => [],
	],
	'upgrades' => [
		'\Elgg\Likes\Upgrades\PublicLikesAnnotations'
	],
	'view_options' => [
		'likes/popup' => ['ajax' => true],
	],
];
