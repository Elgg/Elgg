<?php
/**
 * Edit profile header image
 *
 * @uses vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

echo elgg_view('entity/edit/header', [
	'entity' => $entity,
	'entity_type' => 'user',
	'entity_subtype' => 'user',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
