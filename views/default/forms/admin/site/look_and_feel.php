<?php

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:look_and_feel:info'),
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#help' => elgg_echo('admin:look_and_feel:elgg_branding:help'),
	'label' => elgg_echo('admin:look_and_feel:elgg_branding:label'),
	'name' => 'params[branding]',
]);

echo elgg_view_field([
	'#type' => 'file',
	'#help' => elgg_echo('admin:look_and_feel:favicon:help'),
	'#label' => elgg_echo('admin:look_and_feel:favicon:label'),
	'name' => 'params[favicon]',
]);

echo elgg_view_field([
	'#type' => 'file',
	'#help' => elgg_echo('admin:look_and_feel:walledgarden_background:help'),
	'#label' => elgg_echo('admin:look_and_feel:walledgarden_background:label'),
	'name' => 'params[walledgarden_background]',
]);