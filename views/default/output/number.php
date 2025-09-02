<?php
/**
 * Elgg number output
 * Displays a number as text that was input using number field
 *
 * @uses $vars['value']         The number to display
 * @uses $vars['number_format'] (bool) Should the number be formatted (default: false)
 * @uses $vars['decimals']      (int) Number of decimals to use in formatting (default: 0)
 */

if ((bool) elgg_extract('number_format', $vars, false)) {
	$value = elgg_extract('value', $vars);
	$decimals = (int) elgg_extract('decimals', $vars, 0);
	if (is_numeric($value)) {
		$value = elgg_number_format($value, $decimals);
	}
}

unset($vars['number_format']);
unset($vars['decimals']);

echo elgg_view('output/text', $vars);
