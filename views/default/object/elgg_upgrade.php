<?php
/**
 * ElggUpgrade view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUpgrade) {
	return;
}

if (!$entity->isCompleted()) {
	echo elgg_view('object/elgg_upgrade/pending', $vars);
	return;
}

echo elgg_view('object/elgg_upgrade/completed', $vars);
