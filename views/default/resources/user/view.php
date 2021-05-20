<?php

$user_guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($user_guid, 'user');
$user = get_user($user_guid);

echo elgg_view_page($user->getDisplayName(), [
	'content' => elgg_view_entity($user),
	'sidebar' => false,
	'filter_id' => 'user/view',
]);
