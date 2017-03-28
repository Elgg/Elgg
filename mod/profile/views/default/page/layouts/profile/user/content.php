<?php

$user = elgg_extract('entity', $vars);
if (!$user instanceof ElggUser) {
	return;
}

$content_vars = elgg_extract('content_vars', $vars, []);

echo elgg_view('profile/details', $content_vars);

echo elgg_view_layout('widgets', $content_vars + [
	'num_columns' => 2,
	'owner_guid' => $user->guid,
]);
