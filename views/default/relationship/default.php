<?php
/**
 * Elgg default relationship view
 *
 * @note To add or remove from the relationship menu, register handlers for the menu:relationship hook.
 *
 * @uses $vars['relationship'] the relationship
 */

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

echo elgg_view('relationship/elements/summary', $vars);
