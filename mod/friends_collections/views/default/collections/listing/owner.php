<?php

/**
 * Displays a list of user's friends collections
 *
 * @uses $vars['entity'] Collection owner
 */

$entity = elgg_extract('entity', $vars);
if (!$entity) {
	return;
}

$collections = get_user_access_collections($entity->guid);

echo elgg_view('page/components/list', [
	'items' => $collections,
	'item_view' => 'collections/collection',
	'item_class' => 'elgg-item-access-collection',
	'list_class' => 'elgg-list-access-collections',
	'pagination' => false,
	'no_results' => elgg_echo('friends:collections:no_results'),
]);
