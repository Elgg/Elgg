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
	'#type' => 'hidden',
	'name' => 'container_guid',
	'value' => $group->guid,
]);

echo elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'search',
			'#class' => 'elgg-field-stretch',
			'name' => 'q',
			'required' => true,
			'class' => 'elgg-input-search',
			'placeholder' => elgg_echo('groups:search_in_group'),
			'aria-label' => elgg_echo('groups:search_in_group'), // because we don't add #label
		],
		[
			'#type' => 'submit',
			'icon' => 'search',
			'text' => false,
			'title' => elgg_echo('search:go'),
		],
	],
]);
