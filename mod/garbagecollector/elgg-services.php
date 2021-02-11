<?php

return [
	\Elgg\GarbageCollector\GarbageCollector::name() => \DI\create(\Elgg\GarbageCollector\GarbageCollector::class)
		->constructor(\DI\get('db'), \DI\get('translator')),
];
