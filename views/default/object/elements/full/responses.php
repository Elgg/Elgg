<?php

/**
 * Outputs responses
 *
 * @uses $vars['responses']     Responses HTML
 * @uses $vars['show_add_form'] Boolean to control if a comment add form is allowed to show (default: true)
 */

if (!elgg_extract('show_responses', $vars, true)) {
	return;
}

$entity = elgg_extract('entity', $vars);

$responses = elgg_extract('responses', $vars);
if ($responses === null && ($entity instanceof \ElggEntity)) {
	$responses = elgg_view_comments($entity, (bool) elgg_extract('show_add_form', $vars, true));
}

if (!$responses) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-full-responses',
], $responses);
