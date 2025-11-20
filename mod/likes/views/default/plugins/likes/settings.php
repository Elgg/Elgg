<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggPlugin) {
	return;
}

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('likes:settings:details'),
	'#help' => elgg_echo('likes:settings:details:help'),
	'name' => 'params[details]',
	'value' => $entity->details,
]);
