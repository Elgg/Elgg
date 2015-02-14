<?php

/**
 * Group profile sidebar
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

if (elgg_group_gatekeeper(false, $group->guid)) {
	echo elgg_view('groups/sidebar/my_status', $vars);
	echo elgg_view('groups/sidebar/search', $vars);
	echo elgg_view('groups/sidebar/members', $vars);
}