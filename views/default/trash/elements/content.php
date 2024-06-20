<?php
/**
 * Outputs object content
 *
 * @uses $vars['content'] Content
 */

$content = elgg_extract('content', $vars);
if (!$content) {
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-listing-summary-content'], $content);
