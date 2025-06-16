<?php

/* @var $user \ElggUser */
$user = elgg_get_page_owner_entity();

echo elgg_view_page($user->getDisplayName(), [
	'content' => elgg_view_entity($user),
	'sidebar' => false,
	'filter_id' => 'user/view',
]);
