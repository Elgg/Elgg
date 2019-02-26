<?php

use ElggPlugin\Profile\AnnotationMigration;

return [
	'upgrades' => [
		AnnotationMigration::class,
	],
	'actions' => [
		'profile/edit' => [],
		'profile/fields/reset' => [
			'access' => 'admin',
		],
		'profile/fields/add' => [
			'access' => 'admin',
		],
		'profile/fields/delete' => [
			'access' => 'admin',
		],
		'profile/fields/reorder' => [
			'access' => 'admin',
		],
	],
	'routes' => [
		'view:user' => [
			'path' => '/profile/{username?}',
			'resource' => 'profile/view',
		],
		'edit:user' => [
			'path' => '/profile/{username}/edit',
			'resource' => 'profile/edit',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	]
];
