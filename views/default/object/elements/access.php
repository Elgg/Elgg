<?php

/**
 * Displays information about access of the post
 *
 * @uses $vars['entity']      The entity to show the byline for
 * @uses $vars['access']      Access level of the post
 *                            If not set, will display the access level of the entity (access_id attribute)
 *                            If set to false, will not be rendered
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$access = elgg_extract('access', $vars);
if (!isset($access)) {
	$access = $entity->access_id;
}

if ($access === false || !elgg_is_logged_in()) {
	return;
}

echo elgg_view('output/access', [
	'value' => $access,
]);
