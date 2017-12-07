<?php

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'member_query',
	'required' => true,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('search'),
]);

$footer .= elgg_format_element('p', [
	'class' => 'elgg-text-help',
], elgg_echo('members:total', [get_number_users()]));

elgg_set_form_footer($footer);
