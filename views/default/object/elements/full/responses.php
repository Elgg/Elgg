<?php

/**
 * Outputs responses
 *
 * @uses $vars['responses'] Responses HTML
 */

if (!elgg_extract('show_responses', $vars, true)) {
	return;
}

$entity = elgg_extract('entity', $vars);

$responses = elgg_extract('responses', $vars);
if ($responses === null && ($entity instanceof \ElggEntity)) {
	$responses = elgg_view_comments($entity);
}

if (!$responses) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-full-responses',
], $responses);
