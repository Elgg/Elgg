<?php

elgg_register_menu_item('title', [
	'name' => 'add',
	'icon' => 'plus',
	'text' => elgg_echo('add:object:api_key'),
	'href' => elgg_generate_url('add:object:api_key'),
	'link_class' => [
		'elgg-button',
		'elgg-button-action',
		'elgg-lightbox',
	],
]);

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => ElggApiKey::SUBTYPE,
	'no_results' => true,
]);
