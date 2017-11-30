<?php

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('developers:entity_explorer:guid:label'),
	'name' => 'guid',
	'value' => get_input('guid'),
	'required' => true,
]);

$footer = elgg_view('input/submit', [
	'value' => elgg_echo('submit'),
]);

elgg_set_form_footer($footer);
