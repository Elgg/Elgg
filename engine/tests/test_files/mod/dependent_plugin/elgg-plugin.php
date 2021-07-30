<?php
return [
	'plugin' => [
		'name' => 'Dependent Plugin',
		'version' => '1.9',
		'dependencies' => [
			'parent_plugin' => [
				'position' => 'after',
			],
			'non_existing_plugin' => [
				'must_be_active' => false,
				'position' => 'before',
			],
		],
	],
];
