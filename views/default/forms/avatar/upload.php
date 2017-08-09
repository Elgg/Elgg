<?php
/**
 * Avatar upload form
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

echo elgg_view_field([
	'#type' => 'file',
	'#label' => elgg_echo('avatar:upload'),
	'name' => 'avatar',
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('upload'),
]);

elgg_set_form_footer($footer);
