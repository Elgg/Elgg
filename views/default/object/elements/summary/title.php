<?php

/**
 * Outputs object title
 * @uses $vars['title'] Title
 */

$title = elgg_extract('title', $vars);
if (!$title) {
	return;
}

echo elgg_format_element('h3', [
	'class' => [
		'elgg-listing-summary-title',
	]
], $title);
