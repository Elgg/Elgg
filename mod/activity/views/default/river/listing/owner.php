<?php
/**
 * List user river activity
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  The user to show activity for
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);

$owner_options = [
	'subject_guid' => $entity->guid,
];

$vars['options'] = array_merge($options, $owner_options);

echo elgg_view('river/listing/all', $vars);
