<?php
/**
 * Layout of the projects profile page
 *
 * @uses $vars['entity']
 */

echo elgg_view('projects/profile/summary', $vars);
if (group_gatekeeper(false)) {
	echo elgg_view('projects/profile/widgets', $vars);
} else {
	echo elgg_view('projects/profile/closed_membership');
}
