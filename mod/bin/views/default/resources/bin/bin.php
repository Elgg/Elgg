<?php

$user = elgg_get_page_owner_entity();

$list_params = [
	'type_subtype_pairs' => elgg_entity_types_with_capability('restorable'),
	'owner_guid' => $user->guid,
	'relationship' => 'deleted_by',
	'inverse_relationship' => false,
	'no_results' => true,
];

$content = elgg_call(ELGG_SHOW_DELETED_ENTITIES, function () use ($list_params) {
	return elgg_list_entities($list_params);
});

$title = elgg_echo('default:bin');
if ($user->guid !== elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('default:bin:owner', [$user->getDisplayName()]);
}

echo elgg_view_page($title,	[
	'content' => $content,
	'filter_id' => 'bin',
]);
