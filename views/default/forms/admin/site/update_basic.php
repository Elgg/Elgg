<?php

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'sitename',
	'#label' => elgg_echo('installation:sitename'),
	'value' => elgg_get_config('sitename'),
]);

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'sitedescription',
	'#label' => elgg_echo('installation:sitedescription'),
	'value' => elgg_get_config('sitedescription'),
]);

echo elgg_view_field([
	'#type' => 'email',
	'name' => 'siteemail',
	'#label' => elgg_echo('installation:siteemail'),
	'value' => elgg_get_site_entity()->email,
	'class' => 'elgg-input-text',
]);

echo elgg_view_field([
	'#type' => 'number',
	'name' => 'default_limit',
	'#label' => elgg_echo('installation:default_limit'),
	'value' => elgg_get_config('default_limit'),
	'min' => 1,
	'step' => 1,
]);

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'language',
	'#label' => elgg_echo('installation:language'),
	'value' => elgg_get_config('language'),
	'options_values' => get_installed_translations(),
]);

$footer = elgg_view('input/submit', ['value' => elgg_echo('save')]);
elgg_set_form_footer($footer);
