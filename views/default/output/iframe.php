<?php
/**
 * Display a page in an embedded window
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['src'] Source URL of the page
 */

if (!isset($vars['src']) && isset($vars['value'])) {
	elgg_deprecated_notice('$vars[\'src\'] deprecated in output/iframe for $vars[\'src\']', 1.9);
	$src = $vars['value'];
} else {
	$src = elgg_extract('src', $vars);
}

$src = elgg_normalize_url($src);
$vars['src'] = elgg_format_url($src);

$attributes = elgg_format_attributes($vars);
echo "<iframe $attributes></iframe>";
