<?php
/**
 * Elgg file edit form
 */

$entity = elgg_extract('entity', $vars);

$vars['footer'] = elgg_view_field([
	'#type' => 'submit',
	'text' => $entity instanceof \ElggFile ? elgg_echo('save') : elgg_echo('upload'),
]);

echo elgg_view('forms/entity/edit', $vars);
