<?php
/**
 * Group search form
 *
 * @uses $vars['entity'] ElggGroup
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'q',
	'required' => true,
	'class' => 'elgg-input-search',
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'container_guid',
	'value' => $group->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('search:go'),
]);

elgg_set_form_footer($footer);
