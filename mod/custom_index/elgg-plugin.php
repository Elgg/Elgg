<?php

return [
	'plugin' => [
		'name' => 'Front Page Demo',
		'activate_on_install' => true,
		'dependencies' => [
			'activity' => [
				'must_be_active' => false,
				'position' => 'after',
			],
		],
	],
];
