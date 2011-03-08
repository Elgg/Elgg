<?php
/**
 * Elgg 1.8-svn upgrade 2011030700
 * blog_status_metadata
 *
 * Add a "status" metadata entry to every blog entity because in 1.8 you can have status = draft or
 * status = published
 */
$ia = elgg_set_ignore_access(true);
$options = array(
	'type' => 'object',
	'subtype' => 'blog'
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