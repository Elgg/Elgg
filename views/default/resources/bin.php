<?php

$list_params = array(
    'type' => 'object',
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'no_results' => elgg_echo('no entities'),
    'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable')
);
$content = elgg_list_entities($list_params);
$content_with_buttons = addButtonsToEntities($content);



// Function to add buttons to each entity this will change to registering menu items(maybe)
function addButtonsToEntities($entities) {
    $output = '';

//    if ($entities) {
//        $entitiesList = explode("</li>", $entities);
//        unset($entitiesList[0]);
//
//        foreach ($entitiesList as $entity) {
//            // Extract entity details and add the button
//            $entity_id = substr($entity, strpos($entity, 'id=elgg-object-') + 15, 2);
//            $button = elgg_view('input/submit', array(
//                'name' => 'restore',
//                'value' => 'restore',
//                'onclick' => "if (confirm('Are you sure you want to restore this item? {$entity_id}')) {
//                    disable('{$entity_id}');
//                 }",
//                'href' => elgg_generate_action_url('entity/restore', [
//                    'guid' => $entity_id,
//                    'forward_url' => "/bin",
//                ]),
//            ));
//
//            // Concatenate the entity and button HTML
//            $output .=  $entity . $button  ;
//        }
//    }

    return $entities;
}

echo elgg_view_page('bin', 
elgg_view_layout('admin', [
    'title' => 'bin',
    'content' => $content,
    'filter_id' => 'admin',
]), 'default');

