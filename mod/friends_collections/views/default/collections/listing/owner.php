<?php

/**
 * Displays a list of user's friends collections
 *
 * @uses $vars['entity'] Collection owner
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggEntity)) {
	return;
}

$collections = $entity->getOwnedAccessCollections(['subtype' => 'friends_collection']);

echo elgg_view('page/components/list', [
	'items' => $collections,
	'item_view' => 'collections/collection',
	'item_class' => 'elgg-item-access-collection',
	'list_class' => 'elgg-list-access-collections',
	'pagination' => false,
	'no_results' => elgg_echo('friends:collections:no_results'),
]);
