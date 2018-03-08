<?php

use Elgg\SystemLog\LogEventCache;
use Elgg\SystemLog\SystemLog;

return [
	'system_log.cache' => DI\object(LogEventCache::class)->method('bindToShutdown'),
	'system_log' => \DI\object(SystemLog::class)->constructor(\DI\get('system_log.cache'), \DI\get('db')),
];
