<?php

if (elgg_is_logged_in()) {
	forward('activity');
}

$title = elgg_echo('content:latest');
$content = elgg_list_river([
	'no_results' => elgg_echo('river:none'),
]);

$login_box = elgg_view('core/account/login_box', [
	'class' => 'card',
]);

echo elgg_view_listing_page([
	'identifier' => 'activity',
	'type' => 'all',
	'entity_type' => false,
	'entity_subtype' => false,
], [
	'sidebar' => $login_box,
]);
