<?php
/**
 * Date
 * Displays a properly formatted date
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] Date as text or a Unix timestamp in seconds
 */

// convert timestamps to text for display
if (is_numeric($vars['value'])) {
	$vars['value'] = gmdate('Y-m-d', $vars['value']);
}

echo $vars['value'];
