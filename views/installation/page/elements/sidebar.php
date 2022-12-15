<?php
/**
 * Install sidebar
 *
 * @uses $vars['step'] Current step
 * @uses $vars['steps'] Array of steps
 */

$current_step = elgg_extract('step', $vars);
$steps = elgg_extract('steps', $vars);
if (empty($steps)) {
	return;
}

$current_step_index = array_search($current_step, $steps);

$list_items = '';
foreach ($steps as $index => $step) {
	if ($index < $current_step_index) {
		$class = 'past';
	} elseif ($index == $current_step_index) {
		$class = 'present';
	} else {
		$class = 'future';
	}
	
	$list_items .= elgg_format_element('li', ['class' => $class], elgg_echo("install:{$step}"));
}

echo elgg_format_element('ol', [], $list_items);

$options_values = [];
foreach (_elgg_services()->translator->getInstalledTranslations() as $key => $value) {
	$options_values[elgg_http_add_url_query_elements(elgg_get_current_url(), ['hl' => $key])] = $value;
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('install:change_language'),
	'class' => 'elgg-install-language',
	'name' => 'installer_language',
	'value' => elgg_http_add_url_query_elements(elgg_get_current_url(), ['hl' => elgg_get_current_language()]),
	'options_values' => $options_values,
]);
