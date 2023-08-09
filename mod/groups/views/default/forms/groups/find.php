<?php
/**
 * Group tag-based search form body
 */

echo elgg_view_field([
	'#type' => 'search',
	'name' => 'tag',
	'required' => true,
	'class' => 'elgg-input-search',
	'placeholder' => elgg_echo('groups:search'),
	'aria-label' => elgg_echo('groups:search'), // because we don't add #label
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('search:go'),
]);

elgg_set_form_footer($footer);
