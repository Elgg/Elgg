<?php

echo elgg_view_input('text', [
	'name' => 'guid',
	'value' => get_input('guid'),
	'required' => true,
	'label' => elgg_echo('developers:entity_explorer:guid:label'),
]);

$footer = elgg_view('input/submit', [
	'value' => elgg_echo('submit'),
]);

elgg_set_form_footer($footer);