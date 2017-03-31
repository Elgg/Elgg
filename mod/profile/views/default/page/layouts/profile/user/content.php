<?php

$user = elgg_extract('entity', $vars);
if (!$user instanceof ElggUser) {
	return;
}

echo elgg_view('profile/details', $vars);

echo elgg_view_layout('widgets', $vars + [
	'num_columns' => 2,
	'owner_guid' => $user->guid,
]);
