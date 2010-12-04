<?php
/**
 * Elgg garbage collector language pack.
 *
 * @package ElggGarbageCollector
 */

$english = array(
	'garbagecollector:period' => 'How often should the Elgg garbage collector run?',

	'garbagecollector:weekly' => 'Once a week',
	'garbagecollector:monthly' => 'Once a month',
	'garbagecollector:yearly' => 'Once a year',

	'garbagecollector' => "GARBAGE COLLECTOR\n",
	'garbagecollector:done' => "DONE\n",
	'garbagecollector:optimize' => "Optimizing %s ",

	'garbagecollector:error' => "ERROR",
	'garbagecollector:ok' => "OK",

	'garbagecollector:gc:metastrings' => 'Cleaning up unlinked metastrings: ',
);

add_translation("en", $english);