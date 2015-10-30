<?php

/**
 * Elgg friends collections
 * Lists a user's friends collections
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['collections'] The array of friends collections
 */

$collections = (array) elgg_extract('collections', $vars, array());
if (empty($collections)) {
	echo elgg_format_element('p', ['class' => 'elgg-no-results'], elgg_echo("friends:nocollections"));
	return;
}

$items = '';
foreach ($collections as $collection) {
	$view = elgg_view('core/friends/collection', array(
		'collection' => $collection,
	));
	$items .= elgg_format_element('li', [], $view);
}

echo elgg_format_element('ul', ['class' => 'elgg-friends-collections-list'], $items);
