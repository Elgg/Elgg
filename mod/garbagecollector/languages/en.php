<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'garbagecollector:period' => 'How often should the Elgg garbage collector run?',
	'garbagecollector:period:optimize' => 'Optimize database tables after garbage collector run',

	'garbagecollector:weekly' => 'Once a week',
	'garbagecollector:monthly' => 'Once a month',
	'garbagecollector:yearly' => 'Once a year',

	'garbagecollector' => 'GARBAGE COLLECTOR',
	'garbagecollector:start' => "Garbage collector initialized",
	'garbagecollector:done' => "Garbage collector done",
	'garbagecollector:optimize' => "Optimizing %s",
	
	'garbagecollector:orphaned' => "Cleanup orphaned data from table '%s'",
	'garbagecollector:orphaned:done' => "Cleanup orphaned data done",
	
	'garbagecollector:cli:database:optimize:description' => "Optimize database tables",
);
