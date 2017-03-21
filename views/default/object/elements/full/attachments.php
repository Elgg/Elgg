<?php

/**
 * Outputs attachments
 *
 * @uses $vars['attachments'] Attachments HTML
 */

$attachments = elgg_extract('attachments', $vars);
if (!$attachments) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-full-attachments',
], $attachments);
