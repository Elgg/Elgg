<?php
/**
 * Outputs relationship summary content
 *
 * @uses $vars['content']      Summary content
 */

$content = elgg_extract('content', $vars);
if (elgg_is_empty($content)) {
	return;
}

echo elgg_format_element('div', [
	'class' => [
		'elgg-listing-summary-content',
		'elgg-relationship-content', // @todo remove in 4.0
		'elgg-content',
	]
], $content);
