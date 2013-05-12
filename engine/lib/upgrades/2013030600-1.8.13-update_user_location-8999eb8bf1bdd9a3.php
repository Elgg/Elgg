<?php
/**
 * Elgg 1.8.14 upgrade 2013030600
 * update_user_location
 *
 * Before Elgg 1.8, a location like "London, England" would be stored as an array.
 * This script turns that back into a string.
 */

$ia = elgg_set_ignore_access(true);
$options = array(
	'type' => 'user',
	'limit' => 0,
);
$batch = new ElggBatch('elgg_get_entities', $options);

foreach ($batch as $entity) {
	_elgg_invalidate_query_cache();
	
	if (is_array($entity->location)) {
		$entity->location = implode(', ', $entity->location);
	}
}
elgg_set_ignore_access($ia);
