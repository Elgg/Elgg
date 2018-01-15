<?php
/**
 * RSS comments view
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$options = [
	'guid' => $entity->guid,
	'annotation_name' => 'generic_comment',
	'order_by' => 'n_table.time_created desc',
];

echo elgg_list_annotations($options);
