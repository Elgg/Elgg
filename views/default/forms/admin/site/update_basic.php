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
	'#help' => elgg_echo('installation:sitedescription:help'),
	'value' => elgg_get_config('sitedescription'),
]);

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'language',
	'#label' => elgg_echo('installation:language'),
	'value' => elgg_get_config('language'),
	'options_values' => get_installed_translations(true),
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('installation:registration:label'),
	'#help' => elgg_echo('installation:registration:description'),
	'name' => 'allow_registration',
	'checked' => (bool) elgg_get_config('allow_registration'),
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('installation:walled_garden:label'),
	'#help' => elgg_echo('installation:walled_garden:description'),
	'name' => 'walled_garden',
	'checked' => (bool) elgg_get_config('walled_garden'),
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'email',
	'name' => 'siteemail',
	'#label' => elgg_echo('installation:siteemail'),
	'#help' => elgg_echo('installation:siteemail:help'),
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

$save = elgg_view('input/submit', [
	'value' => elgg_echo('save'),
]);

$save_go = elgg_view('input/submit', [
	'text' => elgg_echo('save_go', [elgg_echo('admin:settings:advanced')]),
	'name' => 'after_save',
	'value' => 'admin/settings/advanced',
]);

$footer = "$save $save_go";
elgg_set_form_footer($footer);
