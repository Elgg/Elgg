<?php
/**
 * Group search form
 *
 * @uses $vars['entity'] ElggGroup
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

echo elgg_view_field([
	'#type' => 'search',
	'name' => 'q',
	'required' => true,
	'placeholder' => elgg_echo('groups:search_in_group'),
	'aria-label' => elgg_echo('groups:search_in_group'), // because we don't add #label
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'container_guid',
	'value' => $group->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('search:go'),
]);

elgg_set_form_footer($footer);
