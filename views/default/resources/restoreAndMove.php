<?php

use Elgg\Database\QueryBuilder;

$entity_id = get_input('entity_id');
$entity = get_entity($entity_id);

$groups = elgg_call(ELGG_IGNORE_ACCESS, function() {
    $defaults = [
        'type' => 'group',
        'relationship' => 'invited',
        'relationship_guid' => elgg_get_logged_in_user_guid(),
        'inverse_relationship' => true,
        'limit' => false,
    ];

    return elgg_get_entities($defaults);
});

echo elgg_view_page(
    elgg_echo('collection:object:bin'),
    elgg_view_layout('admin', [
        'title' => elgg_echo('this is a second page'),
        'content' => $groups,
        'filter_id' => 'admin',
    ]));

