<?php

/**
 * Outputs responses
 *
 * @uses $vars['responses'] Responses HTML
 */

$responses = elgg_extract('responses', $vars);
if (!$responses) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-full-responses',
], $responses);
