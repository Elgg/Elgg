<?php

echo elgg_view_field([
	'#type' => 'userpicker',
	'#label' => elgg_echo('admin:user:label:search'),
	'name' => 'guid',
	'limit' => 1,
	'required' => true,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('makeadmin'),
]);

elgg_set_form_footer($footer);
