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

$vars['src'] = elgg_normalize_url($src);

echo elgg_format_element('iframe', $vars);
