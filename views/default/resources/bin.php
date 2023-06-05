<?php

$list_params = array(
    'type' => 'object',
    'limit' => 4,
    'full_view' => false,
    'list_type_toggle' => false,
    'pagination' => true,
    'owner_guid' => elgg_get_logged_in_user_guid(),
//    'where' => 'soft-deleted = true',
    'no_results' => elgg_echo('no entities')
);
$list_params['type_subtype_pairs'] = [ 'object' => ['blog', 'file', 'bookmarks', 'page'], 'group' => ['group']];
// do we really need to get every item seperately??
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

$body = elgg_view_layout('admin', [
    'title' => 'bin',
    'content' => $content,
    'filter_id' => 'admin',
]);

echo elgg_view_page('bin', $body, 'default');

