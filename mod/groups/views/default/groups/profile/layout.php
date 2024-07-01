<?php
/**
 * Layout of the groups profile page
 *
 * @uses $vars['entity']
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

echo elgg_view('groups/profile/summary', $vars);

if ($group->canAccessContent()) {
	echo elgg_view('groups/profile/widgets', $vars);
}
