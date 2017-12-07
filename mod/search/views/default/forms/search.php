<?php

/**
 * Search form
 *
 * @uses $vars['value'] Current search query
 */
$value = elgg_extract('value', $vars, get_input('q', get_input('tag')));

$search_attrs = elgg_format_attributes([
	'type' => 'text',
]);

echo elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'text',
			'class' => 'search-input',
			'size' => '21',
			'name' => 'q',
			'autocapitalize' => 'off',
			'autocorrect' => 'off',
			'required' => true,
			'value' => _elgg_get_display_query($value),
			'placeholder' => elgg_echo('search'),
		],
		[
			'#type' => 'submit',
			'value' => elgg_echo('search:go'),
		],
	],
]);

$values = [
	'entity_subtype' => get_input('entity_subtype', ''),
	'entity_type' => get_input('entity_type', ''),
	'owner_guid' => get_input('owner_guid'),
	'container_guid' => get_input('container_guid'),
	'search_type' => get_input('search_type', 'all'),
];

foreach ($values as $name => $value) {
	if (!$value) {
		continue;
	}
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => $name,
		'value' => $value,
	]);
}

