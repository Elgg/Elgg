<?php
/**
 * Displays the imprint for the relationship
 *
 * @uses $vars['relationship'] ElggRelationship
 */

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

$imprint = elgg_view('relationship/elements/imprint/contents', $vars);
if (elgg_is_empty($imprint)) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-imprint',
], $imprint);
