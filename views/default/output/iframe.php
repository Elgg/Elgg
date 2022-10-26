<?php
/**
 * Display a page in an embedded window
 *
 * @uses $vars['src'] Source URL of the page
 */

$src = (string) elgg_extract('src', $vars);

$vars['src'] = elgg_normalize_url($src);

echo elgg_format_element('iframe', $vars);
