<?php

/**
 * Default profile layout body
 *
 * @uses $vars['entity'] Entity
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$params = $vars;
$params['full_view'] = true;
$params['show_responses'] = true;
$params['show_summary'] = false;

echo elgg_view_entity($entity, $params);
