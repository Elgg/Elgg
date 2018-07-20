<?php

use Elgg\SystemLog\LogEventCache;
use Elgg\SystemLog\SystemLog;

return [
	LogEventCache::name() => DI\object(LogEventCache::class),

	SystemLog::name() => \DI\object(SystemLog::class)
		->constructor(\DI\get(LogEventCache::name()), \DI\get('db')),
];
