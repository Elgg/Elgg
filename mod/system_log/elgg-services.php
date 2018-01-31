<?php

use Elgg\SystemLog\LogEventCache;
use Elgg\SystemLog\SystemLogInsert;

return [
	'system_log.cache' => DI\object(LogEventCache::class)->method('bindToShutdown'),
	'system_log.insert' => \DI\object(SystemLogInsert::class)->scope(DI\Scope::PROTOTYPE),
];
