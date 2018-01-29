<?php
/**
 * Layout of the groups profile page
 *
 * @uses $vars['entity']
 */

$group = elgg_extract('entity', $vars);

if (!$group instanceof ElggGroup) {
	return;
}

echo elgg_view('groups/profile/summary', $vars);

if ($group->canAccessContent()) {
	if (!$group->isPublicMembership() && !$group->isMember()) {
		echo elgg_view('groups/profile/closed_membership');
	}

	echo elgg_view('groups/profile/widgets', $vars);
} else {
	if ($group->isPublicMembership()) {
		echo elgg_view('groups/profile/membersonly_open');
	} else {
		echo elgg_view('groups/profile/membersonly_closed');
	}
}
