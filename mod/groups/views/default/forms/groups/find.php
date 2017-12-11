<?php
/**
 * Group tag-based search form body
 */

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'tag',
	'required' => true,
	'class' => 'elgg-input-search',
	'placeholder' => elgg_echo('groups:search'),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('search:go'),
]);

elgg_set_form_footer($footer);
