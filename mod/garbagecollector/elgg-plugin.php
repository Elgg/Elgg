<?php

use Elgg\GarbageCollector\CronRunner;

return [
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
