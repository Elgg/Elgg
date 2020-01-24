<?php
/**
 * Displays the imprint for the relationship
 *
 * @uses $vars['relationship'] ElggRelationship
 * @uses $vars['imprint']      An array of imprint elements
 *            				   ['icon_name' => 'calendar', 'content' => 'Starts on Jan 12']
 */

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

echo elgg_view('relationship/elements/imprint/time', $vars);

$imprint = elgg_extract('imprint', $vars);
if (!empty($imprint)) {
	foreach ($imprint as $item) {
		echo elgg_view('object/elements/imprint/element', $item);
	}
}
