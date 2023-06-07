<?php

$list_params = [
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'no_results' => true,
    'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
    //'where' => 'soft-deleted = true'
];


$content = elgg_list_entities($list_params);
$content_with_buttons = addButtonsToEntities($content);


// Function to add buttons to each entity this will change to registering menu items in the plugins themselves
function addButtonsToEntities($entities) {
    $output = '';
    return $entities;
}

echo elgg_view_page(elgg_echo('collection:object:bin'),
elgg_view_layout('admin', [
    'title' => elgg_echo('collection:object:bin'),
    'content' => $content,
    'filter_id' => 'admin',
]), 'default');

