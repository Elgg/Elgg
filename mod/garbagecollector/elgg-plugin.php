<?php

use Elgg\GarbageCollector\CronRunner;

return [
	'plugin' => [
		'name' => 'Garbage Collector',
		'activate_on_install' => true,
	],
	'settings' => [
		'period' => 'monthly',
	],
	'cli_commands' => [
		\Elgg\GarbageCollector\OptimizeCommand::class,
	],
	'hooks' => [
		'cron' => [
			'all' => [
				CronRunner::class => [],
			],
		],
	],
];
