<?php

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('developers:entity_explorer:guid:label'),
	'name' => 'guid',
	'value' => get_input('guid'),
	'required' => true,
	'min' => 1,
]);

$footer = elgg_view('input/submit', [
	'text' => elgg_echo('submit'),
]);

elgg_set_form_footer($footer);
