<?php
/**
 * Displays information about the location and briefdescription of the user
 *
 * @uses $vars['entity']           The user to show the information for
 * @uses $vars['location']         The location of the user
 * @uses $vars['briefdescription'] The briefdescription of the user
 * @uses $vars['imprint']          An array of imprint elements
 *            				       ['icon_name' => 'calendar', 'content' => 'Starts on Jan 12']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

echo elgg_view('user/elements/imprint/location', $vars);
echo elgg_view('user/elements/imprint/briefdescription', $vars);

$imprint = elgg_extract('imprint', $vars);
if (!empty($imprint)) {
	foreach ($imprint as $item) {
		echo elgg_view('object/elements/imprint/element', $item);
	}
}
