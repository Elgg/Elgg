<?php

echo elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'search',
			'#class' => 'elgg-field-stretch',
			'name' => 'member_query',
			'value' => get_input('member_query'),
			'required' => true,
			'class' => 'elgg-input-search',
			'placeholder' => elgg_echo('members:search'),
			'aria-label' => elgg_echo('members:search'), // because we don't add #label
		],
		[
			'#type' => 'submit',
			'icon' => 'search',
			'text' => false,
			'title' => elgg_echo('search'),
		],
	],
]);

$footer = elgg_format_element('p', [
	'class' => 'elgg-text-help',
], elgg_echo('members:total', [elgg_count_entities(['type' => 'user'])]));

elgg_set_form_footer($footer);
