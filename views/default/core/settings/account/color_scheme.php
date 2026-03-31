<?php
/**
 * Provide a way of setting your language prefs
 *
 * @uses $vars['entity'] the user to set settings for
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$user instanceof \ElggUser) {
	return;
}

if (!elgg_get_config('color_schemes_enabled')) {
	return;
}

$css_vars = _elgg_services()->cssCompiler->getCssVars();
if (count($css_vars) < 2) {
	return;
}

$options = [
	'browser' => elgg_echo('user:color_scheme:browser'),
];

foreach ($css_vars as $key => $value) {
	$options[$key] = elgg_echo("color_scheme:{$key}");
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('user:color_scheme:label'),
	'name' => 'color_scheme',
	'value' => $user->elgg_color_scheme ?? 'browser',
	'options_values' => $options,
]);
