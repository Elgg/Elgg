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
];
