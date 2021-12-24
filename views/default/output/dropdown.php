<?php
/**
 * Elgg dropdown display
 * Displays a value that was entered into the system via a dropdown
 *
 * @uses $vars['value'] The text to display
 */

echo htmlspecialchars(elgg_extract('value', $vars, ''), ENT_QUOTES, 'UTF-8', false);
