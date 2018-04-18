<?php
/**
 * Displays information about the author, the time and the access of the post
 *
 * @uses $vars['entity']      The entity to show the information for
 * @uses $vars['byline']      Byline
 *                            If not set, will display default author/container information
 *                            If set to false, byline will not be rendered
 * @uses $vars['show_links']  Owner and container text should show as links (default: true)
 * @uses $vars['time']        Time of the post
 *                            If not set, will display the time when the entity was created (time_created attribute)
 *                            If set to false, time string will not be rendered
 * @uses $vars['time_icon']   Icon name to be used with time info
 *                            Set to false to not render an icon
 *                            Default is 'history'
 * @uses $vars['access']      Access level of the post
 *                            If not set, will display the access level of the entity (access_id attribute)
 *                            If set to false, will not be rendered
 * @uses $vars['access_icon'] Icon name to be used with the access info
 *                            Set to false to not render an icon
 *                            Default is determined by access level ('user', 'globe', 'lock', or 'cog')
 * @uses $vars['imprint']     An array of imprint elements
 *            				  ['icon_name' => 'calendar', 'content' => 'Starts on Jan 12']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

echo elgg_view('object/elements/imprint/byline', $vars);
echo elgg_view('object/elements/imprint/time', $vars);
echo elgg_view('object/elements/imprint/access', $vars);

$imprint = elgg_extract('imprint', $vars);
if (!empty($imprint)) {
	foreach ($imprint as $item) {
		echo elgg_view('object/elements/imprint/element', $item);
	}
}