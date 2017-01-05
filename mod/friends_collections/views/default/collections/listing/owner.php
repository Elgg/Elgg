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
if (empty($collections)) {
	echo elgg_format_element('p', [
		'class' => 'elgg-no-results',
	], elgg_echo('friends:collections:no_results'));
}

$lis = '';
foreach ($collections as $collection) {
	$view = elgg_view('collections/collection', [
		'full_view' => false,
		'collection' => $collection,
	]);

	$lis .= elgg_format_element('li', [
		'class' => 'elgg-item elgg-item-access-collection',
	], $view);
}

echo elgg_format_element('ul', [
	'class' => 'elgg-list elgg-list-access-collections',
], $lis);