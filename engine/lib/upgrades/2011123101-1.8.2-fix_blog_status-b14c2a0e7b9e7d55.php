<?php
/**
 * Elgg 1.8.2 upgrade 2011123101
 * fix_blog_status
 *
 * Most blog posts did not have their status properly set with 1.8 upgrade so we run
 * the blog status upgrade again
 */

$ia = elgg_set_ignore_access(true);
$options = array(
	'type' => 'object',
	'subtype' => 'blog',
	'limit' => 0,
);
$batch = new ElggBatch('elgg_get_entities', $options);

foreach ($batch as $entity) {
	if (!$entity->status) {
		// create metadata owned by the original owner
		create_metadata($entity->getGUID(), 'status', 'published', '', $entity->owner_guid,
			$entity->access_id);
	}
}
elgg_set_ignore_access($ia);