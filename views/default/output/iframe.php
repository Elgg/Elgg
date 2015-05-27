<?php
/**
 * Display a page in an embedded window
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['src'] Source URL of the page
 */

$src = elgg_extract('src', $vars);

$src = elgg_normalize_url($src);
$vars['src'] = elgg_format_url($src);

$attributes = elgg_format_attributes($vars);
echo "<iframe $attributes></iframe>";
