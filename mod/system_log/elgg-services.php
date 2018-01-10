<?php

use Elgg\SystemLog\LogEventCache;
use Elgg\SystemLog\SystemLogInsert;

return [
	LogEventCache::class => DI\object()->method('bindToShutdown'),
	SystemLogInsert::class => \DI\object()->scope(DI\Scope::PROTOTYPE),
];
