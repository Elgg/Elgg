<?php
/**
 * Search form used on admin user listings
 *
 * @uses $vars['additional_search_fields'] additional fields to show on the form. Multi-dimensional array of field definitions
 *                                         that can be used by elgg_view_field()
 */

$additional_search_fields = (array) elgg_extract('additional_search_fields', $vars, []);
foreach ($additional_search_fields as $field) {
	echo elgg_view_field($field);
}

echo elgg_view_field([
	'#type' => 'fieldset',
	'fields' => [
		[
			'#type' => 'text',
			'#class' => 'elgg-field-stretch',
			'name' => 'q',
			'placeholder' => elgg_echo('search'),
			'value' => get_input('q'),
		],
		[
			'#type' => 'submit',
			'value' => elgg_echo('search'),
		],
	],
	'align' => 'horizontal',
]);
