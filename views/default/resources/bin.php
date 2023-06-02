<?php

$list_params = array(
    'type' => 'object',
    'limit' => 4,
    'full_view' => false,
    'list_type_toggle' => false,
    'pagination' => true,
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'where' => 'enabled = true',
    'no_results' => elgg_echo('no entities')
);

// do we really need to get every item seperately??
$list_params['subtype'] = 'blog';
$blogs = elgg_list_entities($list_params, 'list/view');
$blogs_with_buttons = addButtonsToEntities($blogs, 'blog');

$list_params['subtype'] = 'bookmarks';
$bookmarks = elgg_list_entities($list_params, 'list/view');
$bookmarks_with_buttons = addButtonsToEntities($bookmarks, 'bookmarks');

$list_params['subtype'] = 'file';
$files = elgg_list_entities($list_params, 'list/view');
$files_with_buttons = addButtonsToEntities($files, 'file');

$list_params['subtype'] = 'page';
$pages = elgg_list_entities($list_params, 'list/view');
$pages_with_buttons = addButtonsToEntities($pages,'page');

$list_params['type'] = 'group';
$list_params['subtype'] = 'group';
$groups = elgg_list_entities($list_params, 'list/view');
$groups_with_buttons = addButtonsToEntities($groups, 'group');

$content = $pages_with_buttons . " " . $files_with_buttons . " " . $blogs_with_buttons . " " . $groups_with_buttons . " " . $bookmarks_with_buttons;



// Function to add buttons to each entity
function addButtonsToEntities($entities, $type) {
    $output = '';

//    if ($entities) {
//        $entitiesList = explode("</li>", $entities);
//        unset($entitiesList[0]);
//
//        foreach ($entitiesList as $entity) {
//            // Extract entity details and add the button
//            $entity_id = substr($entity, strpos($entity, 'id=elgg-object-') + 15, 2);
//            $button = elgg_view('input/submit', array(
//                'name' => 'delete',
//                'value' => 'delete',
//                'href' => elgg_generate_action_url('entity/delete', [
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

