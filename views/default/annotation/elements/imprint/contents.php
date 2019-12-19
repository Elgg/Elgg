<?php
/**
 * Displays imprint contents
 *
 * @uses $vars['annotation']  The annotation to draw the imprint for
 * @uses $vars['imprint']     An array of imprint elements
 *            				  ['icon_name' => 'calendar', 'content' => 'Starts on Jan 12']
 */

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof ElggAnnotation) {
	return;
}

echo elgg_view('annotation/elements/imprint/byline', $vars);
echo elgg_view('annotation/elements/imprint/time', $vars);

$imprint = elgg_extract('imprint', $vars);
if (!empty($imprint)) {
	foreach ($imprint as $item) {
		echo elgg_view('object/elements/imprint/element', $item);
	}
}
