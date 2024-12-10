<?php
/**
 * Search form
 *
 * @uses $vars['value'] Current search query
 */

$value = (string) elgg_extract('value', $vars, get_input('q', get_input('tag')));

echo elgg_view_field([
	'#type' => 'search',
	'class' => 'search-input',
	'size' => '21',
	'name' => 'q',
	'autocapitalize' => 'off',
	'autocomplete' => 'off',
	'spellcheck' => 'false',
	'required' => true,
	'value' => $value,
	'placeholder' => elgg_echo('search'),
	'aria-label' => elgg_echo('search'), // because we don't add #label
]);

echo elgg_view_field([
	'#type' => 'submit',
	'icon' => 'search',
	'aria-label' => elgg_echo('search'), // because we don't add text
]);

$values = [
	'entity_subtype' => get_input('entity_subtype', ''),
	'entity_type' => get_input('entity_type', ''),
	'owner_guid' => get_input('owner_guid'),
	'container_guid' => get_input('container_guid'),
	'search_type' => get_input('search_type', 'all'),
];

foreach ($values as $name => $value) {
	if (empty($value)) {
		continue;
	}
	
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => $name,
		'value' => $value,
	]);
}
