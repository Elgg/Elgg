<?php
/**
 * Displays information about the author and the time of deletion
 *
 * @uses $vars['entity']     The entity to show the information for
 * @uses $vars['byline']     Byline
 *                           If not set, will display default author/container information
 *                           If set to false, byline will not be rendered
 * @uses $vars['show_links'] Owner and container text should show as links (default: true)
 * @uses $vars['time']       Time of the post
 *                           If not set, will display the time when the entity was created (time_created attribute)
 *                           If set to false, time string will not be rendered
 * @uses $vars['time_icon']  Icon name to be used with time info
 *                           Set to false to not render an icon
 *                           Default is 'history'
 * @uses $vars['imprint']    An array of imprint elements
 *            	             ['icon_name' => 'calendar', 'content' => 'Starts on Jan 12']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

echo elgg_view('trash/elements/imprint/byline', $vars);
echo elgg_view('trash/elements/imprint/time', $vars);
echo elgg_view('trash/elements/imprint/actor', $vars);
echo elgg_view('trash/elements/imprint/type', $vars);

$imprint = elgg_extract('imprint', $vars);
if (!empty($imprint)) {
	foreach ($imprint as $item) {
		echo elgg_view('trash/elements/imprint/element', $item);
	}
}
