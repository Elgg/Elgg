<?php

return [
	'bootstrap' => \Elgg\GarbageCollector\Bootstrap::class,
	'settings' => [
		'period' => 'monthly',
	],
	'cli_commands' => [
		\Elgg\GarbageCollector\OptimizeCommand::class,
	],
];
