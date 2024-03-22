<?php
/**
 * Group tag-based search form body
 */

echo elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'search',
			'#class' => 'elgg-field-stretch',
			'name' => 'tag',
			'required' => true,
			'class' => 'elgg-input-search',
			'placeholder' => elgg_echo('groups:search'),
			'aria-label' => elgg_echo('groups:search'), // because we don't add #label
		],
		[
			'#type' => 'submit',
			'icon' => 'search',
			'text' => false,
			'title' => elgg_echo('search:go'),
		],
	],
]);
