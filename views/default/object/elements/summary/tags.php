<?php
/**
 * Output object tags
 *
 * @uses $vars['entity'] ElggEntity
 * @uses $vars['tags']   HTML for the tags (default is tags on entity, pass false for no tags)
 */

$tags = elgg_extract('tags', $vars, '');
if ($tags === false) {
	return;
}

$entity = elgg_extract('entity', $vars);
if ($tags === '' && $entity instanceof ElggEntity) {
	$tags = elgg_view('output/tags', [
		'entity' => $entity,
	]);
}

echo $tags;
