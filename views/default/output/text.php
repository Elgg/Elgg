<?php
/**
 * Elgg text output
 * Displays some text that was input using a standard text field
 *
 * @uses $vars['value'] The text to display
 */

$value = elgg_extract('value', $vars);
if (!is_scalar($value)) {
	return;
}

echo htmlspecialchars("{$value}", ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
