<?php

use Elgg\Database\QueryBuilder;

$list_params = [
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'no_results' => true,
    'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
    'wheres' => [
        function (QueryBuilder $qb, $main_alias) {
            return $qb->merge($qb->compare("$main_alias.soft_deleted", '=', 'yes', ELGG_VALUE_STRING));
        },
    ],
];
//var_dump(elgg_list_entities_from_relationship_count([
//    'owner_guid' => elgg_get_logged_in_user_guid(),
//    'type' => 'group',
//    'relationship' => 'member',
//    'inverse_relationship' => false,
//    'no_results' => true
//]));

echo elgg_view_page(
    elgg_echo('collection:object:bin'),
    elgg_view_layout('admin', [
        'title' => elgg_echo('collection:object:bin'),
        'content' => elgg_list_entities($list_params),
        'filter_id' => 'admin',
]));

