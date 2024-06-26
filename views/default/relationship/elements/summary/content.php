<?php
/**
 * Outputs relationship summary content
 *
 * @uses $vars['content'] Summary content
 */

$content = (string) elgg_extract('content', $vars);
if (elgg_is_empty($content)) {
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-listing-summary-content'], $content);
