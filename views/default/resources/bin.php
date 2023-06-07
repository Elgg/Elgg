<?php

$list_params = [
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'no_results' => true,
    'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
    //'where' => 'soft-deleted = true'
];

echo elgg_view_page(
    elgg_echo('collection:object:bin'),
    elgg_view_layout('admin', [
        'title' => elgg_echo('collection:object:bin'),
        'content' => elgg_list_entities($list_params),
        'filter_id' => 'admin',
]));

