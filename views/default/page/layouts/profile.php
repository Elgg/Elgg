<?php

/**
 * Displays a layout dedicated to a single entity
 *
 * @see page/layouts/default view for a full list of vars
 * @uses $vars['entity'] Entity
 */

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ElggEntity) {
	elgg_log("\$vars['entity'] is required in page/profile/layout view", 'ERROR');
	return;
}

echo elgg_view('page/layouts/default', $vars);
