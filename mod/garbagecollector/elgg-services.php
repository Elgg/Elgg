<?php

return [
	\Elgg\GarbageCollector\GarbageCollector::name() => \DI\object(\Elgg\GarbageCollector\GarbageCollector::class)
		->constructor(\DI\get('db'), \DI\get('translator')),
];