<?php
/**
 * Testing config defaults
 */

$defaults = [
	'dataroot' => \Elgg\Project\Paths::elgg() . 'engine/tests/test_files/dataroot/',
	'cacheroot' => \Elgg\Project\Paths::elgg() . '/engine/tests/test_files/cacheroot/',
	'site_guid' => 1,
	'icon_sizes' => [
		'topbar' => [
			'w' => 16,
			'h' => 16,
			'square' => true,
			'upscale' => true
		],
		'tiny' => [
			'w' => 25,
			'h' => 25,
			'square' => true,
			'upscale' => true
		],
		'small' => [
			'w' => 40,
			'h' => 40,
			'square' => true,
			'upscale' => true
		],
		'medium' => [
			'w' => 100,
			'h' => 100,
			'square' => true,
			'upscale' => true
		],
		'large' => [
			'w' => 200,
			'h' => 200,
			'square' => false,
			'upscale' => false
		],
		'master' => [
			'w' => 550,
			'h' => 550,
			'square' => false,
			'upscale' => false
		],
	],
	'debug' => 'NOTICE',
];

return $defaults;