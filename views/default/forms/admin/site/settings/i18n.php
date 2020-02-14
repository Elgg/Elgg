<?php
/**
 * Site settings, i18n section.
 */

$result = elgg_view_field([
	'#type' => 'select',
	'name' => 'language',
	'#label' => elgg_echo('installation:language'),
	'value' => elgg_get_config('language'),
	'options_values' => get_installed_translations(true),
]);

$result .= elgg_view_field([
	'#type' => 'checkboxes',
	'#label' => elgg_echo('config:i18n:allowed_languages'),
	'#help' => elgg_echo('config:i18n:allowed_languages:help'),
	'name' => 'allowed_languages',
	'value' => elgg()->translator->getAllowedLanguages(),
	'options_values' => get_installed_translations(),
	'align' => 'horizontal',
]);

echo elgg_view_module('info', elgg_echo('admin:settings:i18n'), $result);
