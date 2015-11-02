<?php

/**
 * Form input help text view
 *
 * @uses $vars['help'] HTML content of the help element
 */
$help = elgg_extract('help', $vars, '');
if (!$help) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-field-help elgg-text-help',
		], $help);
