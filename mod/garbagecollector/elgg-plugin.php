<?php

use Elgg\GarbageCollector\CronRunner;

require_once(__DIR__ . '/lib/deprecated.php');

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
