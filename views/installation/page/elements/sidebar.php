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

echo '<ol>';
foreach ($steps as $index => $step) {
	if ($index < $current_step_index) {
		$class = 'past';
	} elseif ($index == $current_step_index) {
		$class = 'present';
	} else {
		$class = 'future';
	}
	$text = elgg_echo("install:$step");
	echo "<li class=\"$class\">$text</li>";
}
echo '</ol>';

$options_values = [];
foreach (elgg()->translator->getInstalledTranslations() as $key => $value) {
	$options_values[elgg_http_add_url_query_elements(current_page_url(), ['hl' => $key])] = $value;
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('install:change_language'),
	'class' => 'elgg-install-language',
	'name' => 'installer_language',
	'value' => elgg_http_add_url_query_elements(current_page_url(), ['hl' => get_current_language()]),
	'options_values' => $options_values,
]);
