<?php

use Elgg\GarbageCollector\CronRunner;

return [
	'plugin' => [
		'name' => 'Garbage Collector',
		'activate_on_install' => true,
	],
	'settings' => [
		'period' => 'monthly',
		'optimize' => 1,
	],
	'cli_commands' => [
		\Elgg\GarbageCollector\OptimizeCommand::class,
	],
	'events' => [
		'cron' => [
			'all' => [
				CronRunner::class => [],
			],
		],
		'gc' => [
			'system' => [
				'\Elgg\GarbageCollector\GarbageCollector::gcCallback' => [],
			],
		],
	],
];
