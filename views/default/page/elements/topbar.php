<?php

/**
 * Elgg topbar
 * The standard elgg top toolbar
 */
if (!elgg_is_logged_in()) {
	return;
}

echo elgg_view_menu('topbar', [
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
]);
